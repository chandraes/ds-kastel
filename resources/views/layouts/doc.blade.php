<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PO PT IUP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header, .footer {
            text-align: center;
            position: relative;
        }
        .header img {
            position: absolute;
            left: 0;
            top: 0;
            /* height: 80px; */
            width: 150px
        }
        .header h1, .header p {
            margin: 0;
        }
        .header-div {
            margin-left: 150px;
            margin-right: 100px;
        }
        .content {
            margin: 20px;
        }

        .table-items {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .table-items th {
            background-color: #f2f2f2;
            border: 1px solid black;
        }
        .table-items td {
            border: 1px solid black;
            padding: 3px;
        }
        /* table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        } */
        .total {
            text-align: right;
        }
        .signature {
            margin-top: 50px;
        }

        .center-container {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            text-align: center; /* Ensure text elements inside .sub-title are centered as well */
            height: 100vh; /* Make the container take up the full viewport height */
            width: 100vw; /* Optional: Make the container take up the full viewport width */
            margin-bottom: 0;
            margin-top: 0;
        }

        .center-container h3 {
            margin-top:0;
            margin-bottom: 0; /* Removes space below the h3 */
        }

        .center-container p {
            margin-top: 0; /* Removes space above the p */
        }

    </style>
</head>
<body>
    <div class="header">
        @stack('header')
    </div>

    <div class="content">
        @yield('content')

    </div>

    <div class="footer">
        <p>Hormat Kami,</p>
        <p>ADE ZULKARNAIN</p>
        <p>Direktur</p>
    </div>
</body>
</html>
