# **Relayn \- API Management & Permission Control Panel**

Relayn is a powerful, secure, and user-friendly administration panel built on the TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire) using the Filament framework. It acts as a sophisticated proxy and management layer for external APIs, providing granular role-based access control and a rich user interface for interacting with API services.

This application is designed to manage API connections securely, allow different user roles to perform specific actions, and log all significant activities for security and auditing purposes.

## **✨ Core Features**

* **Role-Based Access Control (RBAC)**: Powered by spatie/laravel-permission and seamlessly integrated with bezhanSalleh/filament-shield for easy management of users, roles, and permissions directly within the panel.  
* **Secure API Connection Management**: Store and manage multiple API connections. API keys are encrypted in the database for enhanced security.  
* **Dynamic Navigation**: Navigation menus intelligently appear only when their corresponding API connections are active and configured.  
* **Activity Logging**: Keep track of important user actions, such as creating orders, for auditing and security.  
* **Advanced API Interaction Pages**: Custom-built Filament pages for a rich user experience, including:  
  * **Dashboard Balance Widget**: A real-time view of the API account balance with a skeleton loading effect.  
  * **Service & Product Lists**: Paginated, searchable, and filterable lists of all available services and products.  
  * **Service Order Creation**: An intuitive interface with dynamic filtering by platform and category, real-time cost calculation, and support for complex order types.  
  * **Mass Order Creation**: A versatile tool offering three input methods:  
    1. **Detailed View**: A user-friendly Repeater for adding orders one by one.  
    2. **Quick Input**: A Textarea for power users to paste orders in bulk.  
    3. **Excel Import**: Upload an .xlsx or .xls file to populate orders instantly.  
  * **Order Status Checker**: A utility to check the status of single or multiple orders by their IDs.  
  * **Campaign Management**: A database-backed system to define and manage automated ordering campaigns (requires a scheduled command to be fully operational).

## **🛠️ Tech Stack**

* **Framework**: Laravel  
* **Admin Panel**: Filament  
* **Permissions**: Spatie Laravel Permission & Filament Shield  
* **Activity Logging**: Spatie Laravel Activitylog  
* **Excel Processing**: Maatwebsite Excel

## **🚀 Installation & Setup**

Follow these steps to get your local development environment set up.

**1\. Clone the Repository**

git clone https://github.com/locshino/relayn.git  
cd relayn

**2\. Install Dependencies**

composer install  
npm install  
npm run dev

**3\. Environment Configuration**

\# Copy the example environment file  
cp .env.example .env

\# Generate a new application key  
php artisan key:generate

4\. Configure .env file  
Open the .env file and set up your database credentials. By default, Laravel uses MySQL. If you prefer a simpler setup with SQLite, you can skip this step, but ensure a database/database.sqlite file exists (touch database/database.sqlite).  
For MySQL, update the following lines:

DB\_CONNECTION=mysql  
DB\_HOST=127.0.0.1  
DB\_PORT=3306  
DB\_DATABASE=relayn  
DB\_USERNAME=root  
DB\_PASSWORD=

5\. Run Migrations & Seeders  
This will create all necessary tables and seed the database with initial data (like the 1DG API connection).  
php artisan migrate \--seed

6\. Link Storage  
This step is necessary for features like file uploads and downloads.  
php artisan storage:link

7\. Create Super Admin & Permissions  
Use the integrated Filament Shield commands to set up the first user and generate all necessary permissions for your pages and resources.  
First, create a user with the super\_admin role:

php artisan shield:super-admin

Follow the prompts to create the user.

Next, generate all permissions for your application's resources and pages:

php artisan shield:generate \--all

## **💡 Important Notes**

### **Campaign Feature**

The Campaign Management feature is designed to automatically place orders for new videos on a specified channel. The UI for creating and managing campaigns is fully implemented and stored in the database.

However, the **automated processing logic** requires a real implementation of the app/Services/ChannelScannerService.php. The current file is a **mock** that simulates finding new videos. You will need to replace its logic with actual calls to a third-party API (e.g., YouTube Data API) to fetch new videos from a channel.

### **API Connections**

The application seeds a default connection for the 1DG API. You **must** update this entry with your own valid API key via the "Api Connections" section in the admin panel for the application to function correctly.