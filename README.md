# eCommerce-workshop

- Authentication
  - Login/Register
  - Forget Password
- Products
  - List
  - Show
  - Filter
  - Search
- Categories
  - List
  - Show
- Cart
  - Add/Delete to Cart
- Checkout
  - Show cart summery
  - Apply Coupon (if exists)
- Payment with Paymob
  - Pay with: 
    - Credit Cart
    - Vodafone Cash
- Orders
  - Search
  - Filter
  - Hisory

## Administration Section: 

- Manage 
  - Members
    - List/Show/Create/Update/Delete
  - Staff
    - List/Show/Create/Update/Delete
  - Categories
    - List/Show/Create/Update/Delete
  - Products
    - List/Show/Create/Update/Delete
    - Can be importable
  - Orders
      - List/Show
  - Activity Log
    - List/Filter by staff or date
  - Reports
    - Members count
    - Member registered in period to specific time
    - Orders created in period to specific time
  
## Editor Section: 
  
  - Categories
    - List/Show/Create/Update
  - Products
    - List/Show/Create/Update
    - Can be importable

## Development
- Laravel 9
- MySQL
- Docker
- API Documentation

## CI/CD
- Code Style with php-cs-fixer
- Static Analysis by Psalm


### Tips
- You MUST create your branch from the issue page.
- We will follow **Git Flow**.
- Based on Git Flow, so every issue should be in separate **pull request**
- Commit MUST be like that [module] then descripe your work for example: [product] filter products based on date.
- Commit MUST focus on one feature at once, pleae don't work on many aspects in one commits.
- Make sure your pull request has passed the piplelines.
