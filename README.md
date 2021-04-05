# Mobile Subscription Management 
  
iOS or Google mobile applications will be able to perform in-app-purchase  verification and current subscription control using this API.
On the Worker side, the expire-dates of the current active subscriptions in the database will be checked again on iOS or Google, and their status and expire-dates will be updated.

This project is based on Laravel with running on Docker and use MySql and Redis.
  
## How to run
After cloning this repository, you should run below command to start server :
  
	 ./dev.sh up

In order to access bash in the server and run migrate, you can do it via these commands :
  
	 ./dev.sh bash
	 php artisan migrate:fresh --seed
	 
Now you can access the project via this link [http://localhost:8001]( http://localhost:8001 ) and for phpmyadmin try this [http://localhost:8002]( http://localhost:8002 ) .

## Extra
- You can run and test this API routes with Postman. You can access the collection [here](https://github.com/amir-shadanfar/mobile-subscription-management/tree/main/postman).

- Database schema is designed with [MySQL Workbench]( https://dev.mysql.com/downloads/workbench ). The source files and exported version in PDF is access [here](https://github.com/amir-shadanfar/mobile-subscription-management/tree/main/dbschema).

## Notice
-	One of the most important features of this project using design pattern like Factory and Singleton to make code more flexible and maintainable at choosing OS types and API's of OS
-	Second one, using factories and repositories to make test easily
-	Third one is caching the necessary part of data to increase the performance
-	Forth benefit is the qualification of working on Docker 