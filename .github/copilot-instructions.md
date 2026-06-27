# Copilot Instructions for SimpleLab

- This is a Laravel 13 application with its main logic in `app/Http/Controllers`, routes defined in `routes/web.php`, Blade templates in `resources/views`, and database definitions in `database/migrations`.
- The app is a lab equipment and room booking system with RFID integration: `app/Http/Controllers/RfidController.php` handles asset/user card registration, authentication, asset tracking, and RFID-driven borrowing.
- User roles are explicit strings: `user`, `dosen`, and `admin`. Many controllers use `auth()->user()->role` checks rather than Laravel policies.
- Equipment status values are normalized in the schema: `available`, `borrowed`. Loan status values include `active`, `returned`, `cancelled`. Room booking statuses include `pending`, `approved`, `rejected`, `cancelled`.
- Separate auth flows exist for normal users, dosen, and admin-created dosen accounts: `showLogin`, `showLoginDosen`, `showLoginMahasiswa`, `register`, `registerDosen`, and `createDosenByAdmin`.
- Routes are grouped under `auth` middleware; all protected app flows go through `routes/web.php`.
- The main dashboard flow branches by role in `DashboardController@index`: admins see assets, users, logs, and reports; regular users see available equipment, schedules, and personal loan history.
- For new features, update both `routes/web.php` and the appropriate controller; Blade views under `resources/views` are used directly by controllers.
- Build/setup commands:
  - `composer run setup` installs PHP dependencies, copies `.env.example`, generates app key, migrates database, installs npm deps, and builds assets.
  - `npm run dev` starts Vite.
  - `composer run dev` starts Laravel serve, queue listener, logs, and Vite concurrently.
  - `composer run test` runs Laravel tests after clearing config.
- Don’t assume there is a custom policy layer; authorization is often enforced by `if ($user->role !== 'admin') abort(403)` inside controllers.
- When editing booking logic, preserve the conflict checks in `PeminjamanController::borrowRuangan()` against `JadwalLab` and approved `PeminjamanRuangan` entries.
- When adding RFID-related behavior, keep the separation between `TagRfid` and `RfidCard` models: item tags are `tag_rfids`, user cards are `rfid_cards`.
- Keep the file naming and location conventions of Laravel: models under `app/Models`, controllers under `app/Http/Controllers`, route definitions in `routes/`, assets in `resources/css` and `resources/js`.
- There is no existing `.github/copilot-instructions.md`, so use these project-specific conventions as the reference for future AI edits.
