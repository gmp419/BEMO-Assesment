# Smart Ticket Triage – Laravel / Vue 3 Take-Home Assessment

> Production-style single-page application for help-desk teams  
> – submit tickets, queue AI classification, review / override categories, view analytics.

---

## ⚙️ 10-Step Setup (Windows / macOS / Linux)

1. **Clone**  
   ```bash
   git clone https://github.com/gmp419/BEMO-Assesment.git
   cd BEMO-Assesment
   
2. **Install PHP deps**
   composer install

3. **Install JS deps**
   npm install

4. **Environment**
   cp .env.example .env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tickets
   DB_USERNAME=root
   DB_PASSWORD=secret
   QUEUE_CONNECTION=database
   OPENAI_API_KEY=sk-xxx
   OPENAI_CLASSIFY_ENABLED=true   # false = dummy data

5. **Laravel key**
   php artisan key:generate

6. **DB & seed**
   php artisan migrate --seed   # 25 sample tickets

7. **Queue worker (second terminal)**
   php artisan queue:work

8. **Build assets**
   npm run dev

9. **Serve**
   php artisan serve

10. **Open**
   http://localhost:8000 → done!

**🧪 Assumptions & Trade-offs**

    Local MySQL required (no Docker to keep setup < 10 steps).
    Client-side pagination keeps backend simple; scalable version would use server-side.
    File-based queue avoids extra services.
    Plain CSS/BEM satisfies “no CSS frameworks” rule.

**🚀 With More Time**

    Laravel Horizon dashboard.
    Server-side search & pagination.
    Role-based auth.
    Dark/light theme toggle.
    Use Tailwind to make it uesr responsive