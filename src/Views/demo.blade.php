<!DOCTYPE html>
<html>
<head>
    <title>Ecpay Demo Page</title>
</head>
<body>
	<form action="ecpay_demo_201702/checkout">
		<h1>Ecpay Demo</h1>
		<hr>
		<h2>ChoosePayment</h2>
		<select name="payway">
	        <option value="ALL" selected>ALL</option>
	        <option value="Credit">Credit</option>
	        <option value="CVS">CVS</option>
	    </select>
		<input type="submit" value="CheckOut" />
	</form>
	<br>
	<br>
	See Example : <a href="https://github.com/ECPay/ECPayAIO_PHP/blob/master/AioSDK/example">https://github.com/ECPay/ECPayAIO_PHP/blob/master/AioSDK/example</a>
</body>
</html>
