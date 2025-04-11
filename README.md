# CGRD TEST 

### How to set up

- docker compose up -d --build
- docker exec -it php_app composer install
- Navigate to http://localhost:8080


### Application Notes

I was unsure if I can use PSR Interfaces for Request and Response etc.. so I implemented basic ones just for showcasing of DI.

### Todos
- Live JS refresh with jQuery
- csrf , xss procetion
- error handler

#### Unsure if needed
- CI/CD
- Xdebug
- Some code checks/hooks/tools like psalm, csfix
- Any branching strategy 
- PSR for better interfaces like Request or Response
- Unit Tests / Coverage
- Logger (Based on PSR interface also)
- Exception handling
- Generic Validator class for User Input
- .env File approach currently I Will hardcode it into config files.
- Migrations for DB
