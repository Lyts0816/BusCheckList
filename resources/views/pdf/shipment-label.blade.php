<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 5px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
        }

        .label {
            width: 3in;
            height: 4in;
            border: 1px solid #000;
            box-sizing: border-box;
            padding: 6px;
        }

        .header {
            width: 100%;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            margin-bottom: 4px;
        }

        /* .logo {
            width: 34px;
            height: 34px;
            border: 1px solid #000;
            border-radius: 50%;
            float: left;
            overflow: hidden;
            box-sizing: border-box;
        }

        .logo img {
            width: 100%;
            height: 100%;
            display: block;
        } */

        .logo {
            width: 40px;
            height: 40px;
            float: left;
            overflow: hidden;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .company {
            margin-left: 25px;
            padding-top: 1px;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
        }

        .city {
            font-size: 10px;
            font-weight: bold;
        }

        .clear {
            clear: both;
        }

        .row {
            margin-bottom: 3px;
        }

        .label-title {
            font-weight: bold;
        }

        .section {
            border-top: 1px solid #000;
            padding-top: 3px;
            margin-top: 3px;
        }

        .section-header {
            font-weight: bold;
            font-size: 10px;
            background: #f0f0f0;
            padding: 2px;
        }

        .barcode {
            border-top: 1px solid #000;
            margin-top: 5px;
            padding-top: 5px;
            text-align: center;
        }

        .tracking-text {
            font-size: 9px;
            font-weight: bold;
            margin-top: 2px;
        }

        .small {
            font-size: 8px;
        }
    </style>
</head>
<body>

<div class="label">

    <div class="header">
        <div class="logo">
            <img src="{{ public_path('images/only_logo.png') }}" alt="Logo">
        </div>

        <div class="company">
            <div class="company-name">
                YELLOW BUS LINE INC.
            </div>

            <div class="city">
                City of Koronadal
            </div>
        </div>

        <div class="clear"></div>
    </div>

    <div class="row">
        <span class="label-title">DATE:</span>
        {{ optional($shipment->created_at)->format('m/d/Y') }}
    </div>

    <div class="row">
        <span class="label-title">OR #:</span>
        {{ $shipment->or_number }}
    </div>

    <div class="row">
        <span class="label-title">TRACKING #:</span>
        {{ $shipment->tracking_number }}
    </div>

    <div class="section">
        <div class="section-header">
            SENDER
        </div>

        <strong>{{ $shipment->sender_name }}</strong><br>

        <span class="small">
            {{ $shipment->sender_address }}
        </span><br>

        {{ $shipment->sender_contact }}
    </div>

    <div class="section">
        <div class="section-header">
            RECIPIENT
        </div>

        <strong>{{ $shipment->recipient_name }}</strong><br>

        <span class="small">
            {{ $shipment->recipient_address }}
        </span><br>

        {{ $shipment->recipient_contact }}
    </div>

    <div class="section">
        <div>
            <strong>BOX #:</strong>
            {{ $shipment->box_number }}
        </div>

        <div>
            <strong>DESTINATION:</strong>
            {{ $shipment->destination_terminal }}
        </div>
    </div>

    <div class="barcode">

        {!! DNS1D::getBarcodeHTML(
            $shipment->tracking_number,
            'C128',
            1.4,
            40
        ) !!}

        <div class="tracking-text">
            {{ $shipment->tracking_number }}
        </div>

    </div>

</div>

</body>
</html>