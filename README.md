## Magento2 Add filter like 24 hours, 30 days etc.. for My Orders on Sales Order History

> Magento2 an open-source e-commerce platform written in PHP.

&nbsp;
&nbsp;

> In this extension we will learn how to filter & check their order history. 

&nbsp;
&nbsp;

> In this extension, we will learn how to add filters of 24 hours, 30 days, 6 months for more ease to customer.

&nbsp;
&nbsp;

## Installation Steps

##### Step 1 : Download the Zip file from Github & Unzip it
##### Step 2 : Create a directory under app/code/Binstellar/Orders
##### Step 3 : Upload the files & folders from extracted package to app/code/Binstellar/Orders
##### Step 4 : Go to the Magento2 Root directory & run following commands

php bin/magento setup:upgrade

php bin/magento setup:di:compile

php bin/magento setup:static-content:deploy -f

php bin/magento cache:flush

&nbsp;
&nbsp;

<h5> 6 months filter </h5>
<kbd>

![image1](https://user-images.githubusercontent.com/123800304/218650337-150edb8e-fe8b-4bb8-865b-3345e108a91b.png)

</kbd>

&nbsp;
&nbsp;

<h5> 24 Hours filter </h5>
<kbd>

![image2](https://user-images.githubusercontent.com/123800304/218650563-5727c79d-2865-4a6f-b0e2-78984bc2a385.png)

</kbd>

&nbsp;
&nbsp;
## Note : We have tested this option in Magento ver. 2.4.5-p1
