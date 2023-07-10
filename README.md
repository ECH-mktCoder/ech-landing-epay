# ech-landing-epay
A Wordpress plugin for ECH landing page epayment. It integrates with ECH ePay. A confirmation email will be sent to customer once the payment is completed. 

## Usage 
1. Enter `Auth Token`(provided by IT) in the dashboard setting page "LP ePay" 
2. To generate a payment button, copy the below shortcode sample to start. 
```
[display_epay amount=100 duedate=1]
```

## Shortcode attributes
Based on the requirments, change the attributes or values if necessary.

Attribute | Description
----------|-------------
`amount` (INT) | Price. Default is 100
`duedate` (INT) | Payment link expiration date from current date. Count in days. Default is null, no due date is set. 