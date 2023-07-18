# ech-landing-epay
A Wordpress plugin for ECH landing page epayment. It integrates with ECH ePay. A confirmation email will be sent to customer once the payment is completed. 

## Usage 
1. Enter `Auth Token`(provided by IT) in the dashboard setting page "LP ePay" 
2. To generate a payment button, copy the below shortcode sample to start. 
```
[display_epay amount=100 duedate=1 email_price_content="<p>1. Price Item 1</p><p>2. Price Item 2</p>" email_subject="成功網上付款通知" email_sender="BRAND_NAME" email_replyto="sample@test.com"]
```

## Shortcode attributes
Based on the requirments, change the attributes or values if necessary.

Attribute | Description
----------|-------------
`amount` (INT)[required] | Price. Default is 100
`duedate` (INT) | Payment link expiration date from current date. Count in days. Default is null, no due date is set. 
`email_price_content` (String) [required] | In HTML format. Price content which shows in customer confirmation email. 
`email_subject` (String) [required] | Email subject
`email_sender` (String) [required] | Sender name. Eg: DR REBORN 
`email_replyto` (String) [required] | Email address that customer reply to


Below is the email content template
親愛的會員

您好，多謝您對{{email_sender}}的支持，「網上預付${{amount}}獨家禮遇」已成功付款，付款參考編號為[ref code]。到店或完成療程後即獲贈以下禮遇！

{{email_price_content}}

如有任何疑問，請回覆向我們查詢。

謹啟
{{email_sender}}