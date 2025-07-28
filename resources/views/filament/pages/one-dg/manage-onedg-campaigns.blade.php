<x-filament-panels::page>

    <x-filament::tabs>
        <x-filament::tabs.item :active="$activeTab === 'campaigns'" wire:click="$set('activeTab', 'campaigns')"
            icon="heroicon-m-queue-list">
            Chiến dịch
        </x-filament::tabs.item>

        <x-filament::tabs.item :active="$activeTab === 'configs'" wire:click="$set('activeTab', 'configs')"
            icon="heroicon-m-cog-6-tooth">
            Cấu hình
        </x-filament::tabs.item>
    </x-filament::tabs>

    <div class="mt-4">
        {{ $this->table }}
    </div>

    <x-filament::section heading="Lưu ý quan trọng" class="mt-8" color="warning" collapsible>
        <div class="prose dark:prose-invert">
            <p>
                Tính năng "Chiến dịch" yêu cầu một tác vụ chạy nền (scheduled job) để tự động quét các kênh và đặt hàng.
                Giao diện này chỉ giúp bạn tạo và quản lý các chiến dịch/cấu hình trong cơ sở dữ liệu.
            </p>
            <p>
                Để chức năng này hoạt động đầy đủ, bạn cần phải:
            </p>
            <ol>
                <li>Tạo một <a href="[https://laravel.com/docs/scheduling](https://laravel.com/docs/scheduling)"
                        target="_blank" rel="noopener">Laravel Scheduled Command</a> chạy định kỳ (ví dụ: mỗi 5 phút).
                </li>
                <li>Trong command đó, viết logic để lấy các chiến dịch đang hoạt động.</li>
                <li>Sử dụng API của nền tảng (ví dụ: YouTube Data API) để tìm các video mới của kênh.</li>
                <li>Với mỗi video mới, lặp qua các dịch vụ trong cấu hình của chiến dịch và gọi
                    <code>OneDgApiService->addOrder()</code>.</li>
            </ol>
            <p>
                Đây là một tác vụ phức tạp và nằm ngoài phạm vi của trang giao diện này.
            </p>
        </div>
    </x-filament::section>

</x-filament-panels::page>
