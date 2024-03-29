# LazyMealPrep

Make Meal Prep easier by automating the shopping experience. Minimum will probably be a team of 2-3 but there are many ways in which this project can be expanded if more people with to join.

# Technical
 
The base components of the project are a calendaring function, a recipe function, and integration with Walmart Pickup. 

## Calendaring

Our users will be able to assign their recipes (or recipes marked as public) to meals for specific days (Breakfast, Lunch Elevensies etc.)

## Recipes

We need a nice and easy ui to be able to input recipes and display them for usage later on. Another extension would be to allow for importing from popular recipe plugins for easy implementation for professional meal planners.

## Walmart Pickup

This will be the core part of the functionality because it provides the most benefit to those of us that are extremely lazy, but want to plan out meals. Based off of how often the user wishes to go grocery shopping we will generate a shopping list for them and automatically add it to their pickup cart. This will aid in the speed of shopping and will reduce the possibility of going off of the plan because you never even go into the store.

## Databases

We will be using Postgres for the relational database and Redis for the NoSQL (these are still flexible).


# Business

This will be a two tiered business. Free users will be able to input their own recipes and create Walmart orders. Premium users will be those who provide meal plans for customers and will be a service that they can subscribe to to make the process easier for their customers.

# Legal

We will keep user data to a minimum. The only location data we will save will be their preferred pickup store and whatever data is required to use the Walmart API on their behalf. Payment from premium users will be through a third party such as Stripe.
