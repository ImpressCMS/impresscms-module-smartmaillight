/**
* $Id: readme.txt,v 1.1 2007/09/23 20:41:03 marcan Exp $
* Module: SmartMailLight
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

What is SmartMailLight ?
======================

SmartMailLight is a XOOPS 2 module that allows you to manage invoices with your clients. It allows the creation of accounts, invoices and payments. It has currently no user side for the clients to see their invoice, but this shall come, as well as other exciting features !

SmartMailLight was built because we needed it to manage our client's invoice. So it is very tied to our needs. However, we thought it could still be usefull to some of you out there ! And if you like it and we get enough feedback, then we surely will improved it to make it more generic along the run !

How to install SmartMailLight
===========================

The installation procedure is described in the install.txt file.

How does it work ?
==================

SmartMailLight does not "produce" the invoices for you but allows you to easily manage them. Here is a quick description of who we use the module.

When we need to invoice a new client, we produce the invoice and save it in a PDF file so it can easily be sent to the client. Then, we need to manage the invoice to be sure it gets paid some time ;-) ! This is where SmartMailLight comes in ! If this is a new client, we need to create a new account.

Please note that currently, everything happens in the admin side of the module. A user side where client can view their invoice shall come later. So, to create a new account : in the Accounts tab of the module, click on Add an account. Enter the information you need and click on the Submit button. A new Account (client / organisation / individual) has now been created. We can now add an invoice for this account !

Still under the Accounts tab, find the account you just created and click on the "Create a new invoice for this account" in the Actions column of the table. You can now fill in those fields :

- Account : account to which is linked the new invoice you want to add in the system. If you have followed the previous steps, this should already be set to the new Account we have created a few minutes ago.
- Invoice number : this is the identifying number or sring of your invoice. You can set it top whatever you want.
- Amount : amount of the invoice to be paid by the client.
- Currency : currency of the amount to be paid by the client. Please note that the currencies are managed by SmartObject.
- Method : how this invoice is likely to be paid : by check or by PayPal.
- Invoice file : the actual invoice file that you sent to the client. Currently, it supports PDF, Word and Excel document.
- Date : date the invoice was sent to the client
- Paid : flag to mark if the invoice has been paid or not. This field will automatically be set to Yes when according payment(s) will have been made related to this invoice.
- Notes : free text field to enter any relevant notes.

Then, when we receive a payment for this invoice, then we need to inform the module we have receive funds related to this invoice. To do so, under the Invoices tab, locate the related invoice and click on the "Add a paymentg for this invoice" button in the Actions column. On the next form, you can add the amount that have been paid and hit the Submit button. If the total amount of payments related to this invoice equals the amount of the invoice, the Paid field of this invoice will be set to Yes.

Another cool thing is that under the Invoices tab, you can filter the invoice to only see the invoices that are still not paid. Simply select Standing Invoices in the Filter select box.

And finally, still under the Invoices tab, you can see a gree box where the module displays the amount of money that is yet to be received in your default currency set in SmartObject.

Feedback
========

As usual, feedback is very welcomed ! We would like to know if you like the module, if it has bugs or if it can be in any way improved ! You can do all this in the SmartMailLight Community Forum :

http://smartfactory.ca/modules/newbb/viewforum.php?forum=33

Enjoy SmartMailLight !

.:: The SmartFactory ::.
.:: Open Source : smartfactory.ca ::.
.:: Professionnal : inboxinternational.com ::.