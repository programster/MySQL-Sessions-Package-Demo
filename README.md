# MySQL Sessions Package Demo
A demonstration codebase showing how one can use the MySQL sessions PHP Package.

### Requirements
* Docker
* Docker Compose

## Example Usage
First, copy the `.env.example` file to `.env` and fill in a strong password for the database. MariaDB will fail to initialize the database if you don't set a strong password.

```bash
docker-compose build
docker-compose up -d
```

Now if you go to `http://localhost` in your browser, you should see it output a time value from the session, or state that no session value was set (on first loading). You can also enter the database to see that the database is indeed setting a value in the `sessions` table.
