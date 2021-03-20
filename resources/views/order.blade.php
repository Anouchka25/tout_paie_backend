<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>ToutPaie - Order</title>
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #ddd;
        color: black;
        font-size: 16px;
        font-weight: 500;
    }

    .calculation {
        color: black;
        font-size: 16px;
        font-weight: 500;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }

    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title" width="350px;">
                                <!-- <img src="https://www.sparksuite.com/images/logo.png" style="width:100%; max-width:300px;"> -->
                                Order
                            </td>
                            <td></td>
                            <td style="padding: 0px !important;">
                                Order #: {{ $OrderID }}<br>
                                Created: {{ $OrderDate }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td width="350px;">
                                {{ $ResidenceCity }}<br>
                                {{ $ResidenceStreetName }}<br>
                                {{ $ResidencePostalCode }}
                            </td>
                            <td></td>
                            <td>
                            Last Name :  {{$LastName}}<br>
                            First Name : {{ $FirstName }} <br>
                            Delivery address : {{$ResidenceStreetName}} <?php echo ','; ?> {{$City}} <?php echo ','; ?> {{$ResidencePostalCode}} <?php echo '.'; ?><br>
                            Phone Number :{{$MobileNumber}} <br>
                            Email :   {{ $Email }} <br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>Product Name</td>
                <td>Product Type</td>
                <td>Product Description</td>
                <td>DeliveryMode</td>
                <td>Quantity</td>
                <td>Price</td>
            </tr>
            
            @if(isset($OrderItems))
                @foreach($OrderItems as $items)
	            <tr class="item">
	                <td>
	                    {{ $items->ProductName }}
	                </td>
                    <td>
	                    {{ $items->ProductType }}
	                </td>
	                <td>
	                    {{ $items->ShortDescription }}
	                </td>
                    <td>
	                    {{ $items->DeliveryMode }} <?php if($items->DeliveryMode=='H'){ echo 'At Home';} else{ echo 'At Store';} ?>
	                </td>
	                <td>
	                    {{ $items->Quantity }}
	                </td>
	                <td>
	                    {{ $items->Amount }}
	                </td>
	            </tr>
            @endforeach
                @endif
          
            <tr class="total">
                <td></td>
                <td></td>
                <td colspan="2" class="calculation">
                   Total Amount: {{ $TotalAmount }}
                </td>
            </tr>

            <tr class="total">
                <td></td>
                <td></td>
                <td colspan="2" class="calculation">
                    Delivery Amount: {{ $TotalDeliveryAmount }}
                </td>
            </tr>

            <tr class="total">
                <td></td>
                <td></td>
                <td colspan="2"  class="calculation">
                   Total TTC: {{ $TotalAmount + $TotalDeliveryAmount }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>