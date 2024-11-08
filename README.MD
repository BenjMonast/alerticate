# Alerticate

Welcome to **Alerticate**, a web application designed to send notifications in the event of an emergency during travel. By setting up an automated alert, Alerticate can notify your designated contacts if you don't return from a trip within a specified timeframe.

## Live Demo

Check out the live version at [alerticate.com](https://alerticate.com).

## Features

- **Automated Alerts**: Schedule notifications that will automatically send to your contacts if you do not check in by a specific time.
- **Customizable Contact List**: Easily add and manage your emergency contacts within the platform.
- **Flexible Notifaction**: Choose to notify your contacts through Email, SMS, or both.

## How It Works

1. **Add Emergency Contacts**:
   - Specify the people who should receive an alert if you don't check in on time.

2. **Set Up Your Trip Details**:
   - Input information about your trip, including start time, expected return time, and other relevant details.
  

3. **Create and Confirm Alert**:
   - Confirm your trip and the alert parameters. Alerticate will handle the rest!

4. **Stay in Touch**:
   - If you return safely and within the expected time, the alert is automatically disabled. Otherwise, Alerticate will automatically notify your emergency contacts on your behalf.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/BenjMonast/alerticate.git
   cd alerticate
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Configure environment:
   - Copy `.env.example` to `.env` and update the necessary environment variables (database credentials, application key, etc.)
   ```bash
   php artisan key:generate
   ```

4. Run migrations:
   ```bash
   php artisan migrate
   ```

5. Start the application:
   ```bash
   php artisan serve
   ```

## Technologies Used

- **Backend**: Laravel (PHP)
- **Frontend**: Blade (HTML)
- **Database**: MySQL
- **Additional Tools**: Bootstrap for styling, Webpack for asset management

## Contributing

Pull requests are always welcome! For questions or support, submit an issue on GitHub.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

