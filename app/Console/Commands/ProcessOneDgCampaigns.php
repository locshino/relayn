<?php

// app/Console/Commands/ProcessOneDgCampaigns.php

namespace App\Console\Commands;

use App\Models\OneDgCampaign;
use App\Services\ChannelScannerService;
use App\Services\OneDgApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessOneDgCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onedg:process-campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan channels for new videos and create orders based on active campaigns.';

    /**
     * Execute the console command.
     */
    public function handle(ChannelScannerService $scanner, OneDgApiService $apiService): void
    {
        $this->info('Starting to process 1DG campaigns...');

        // Get all active campaigns that have not expired.
        $campaigns = OneDgCampaign::where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get();

        if ($campaigns->isEmpty()) {
            $this->info('No active campaigns to process. Exiting.');

            return;
        }

        $this->info("Found {$campaigns->count()} active campaigns.");

        foreach ($campaigns as $campaign) {
            $this->line("Processing campaign #{$campaign->id} for channel: {$campaign->channel_link}");

            $processedVideos = $campaign->processed_videos ?? [];

            // Get new videos from the channel, excluding already processed ones.
            $newVideos = $scanner->getNewVideos($campaign->channel_link, $processedVideos);

            if (empty($newVideos)) {
                $this->line('-> No new videos found for this campaign.');

                continue;
            }

            $this->info('-> Found '.count($newVideos).' new video(s). Placing orders...');

            foreach ($newVideos as $video) {
                $this->line("--> Processing video: {$video['link']}");

                // Loop through all services configured for this campaign.
                foreach ($campaign->campaignConfig->services as $serviceConfig) {
                    try {
                        $response = $apiService->addOrder(
                            $serviceConfig['service_id'],
                            $video['link'],
                            $serviceConfig['quantity']
                        );

                        if (isset($response['order'])) {
                            $this->info("----> Successfully placed order for service #{$serviceConfig['service_id']}. Order ID: {$response['order']}");
                        } else {
                            $this->error("----> Failed to place order for service #{$serviceConfig['service_id']}: ".($response['error'] ?? 'Unknown API error'));
                        }
                    } catch (\Exception $e) {
                        $this->error("----> An exception occurred for service #{$serviceConfig['service_id']}: {$e->getMessage()}");
                        Log::error("Campaign processing failed for service {$serviceConfig['service_id']}", ['exception' => $e]);
                    }
                }

                // Add the video ID to the processed list and save the campaign.
                $processedVideos[] = $video['id'];
            }

            $campaign->processed_videos = $processedVideos;
            $campaign->save();
        }

        $this->info('Finished processing all campaigns.');
    }
}
