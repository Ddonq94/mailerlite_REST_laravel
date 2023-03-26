MailerLite REST API wrapper

To setup

-   Create .env using the .env.example file as a template
-   Create a database and update the credentials in the env file
-   Run PHP artisan serve
-   Run PHP artisan migrate
-   Visit the base URL on your host (e.g): http://127.0.0.1:8000/
-   Add a new user or select one from the list
-   Manage selected user's subscribers as you desire

What is left

-   Filter API doesnt seem to work (maybe I will fix this someday)
-   For the edit and delete endpoints, email address was used, ID did not work.
-   the Adding user form still needs work
