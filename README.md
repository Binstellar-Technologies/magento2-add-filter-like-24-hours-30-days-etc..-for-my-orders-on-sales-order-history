## Magento2 Add filter like 24 hours, 30 days etc.. for My Orders on Sales Order History

> Magento2 an open-source e-commerce platform written in PHP.

> In this extension we will learn how to filter & check their order history. 

> In this extension, we will learn how to add filters of 24 hours, 30 days, 6 months for more ease to customer.


## Installation Steps

##### Step 1 : Download the Zip file from Github & Unzip it
##### Step 2 : Create a directory under app/code/Binstellar/Orders
##### Step 3 : Upload the files & folders from extracted package to app/code/Binstellar/Orders
##### Step 4 : Go to the Magento2 Root directory & run following commands

php bin/magento setup:upgrade

php bin/magento setup:di:compile

php bin/magento setup:static-content:deploy -f

php bin/magento cache:flush


## Note : We have tested this option in Magento ver. 2.4.5-p1