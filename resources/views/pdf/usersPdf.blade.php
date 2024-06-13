<!DOCTYPE html>
<html>
<head>
    <title>Border Life - LIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
        }
        .sub-header {
            /* background-color: #22416b; */
            color: #22416b;
            padding: 20px;
            text-align: center;
        }
        .info-container {
            width: 45%; /* Adjust width as needed */

            margin: 10px; /* Add some margin for spacing */
            border: 1px solid #ccc; /* Add border for visualization */
            padding: 10px; /* Add padding for spacing */
        }
    </style>
</head>
<body>
    {{-- <div class="sub-header"> --}}
        <div >
            <!-- Left side content -->
            <h1>Border Life - LIS</h1>
            <p>Date: {{ date('m/d/Y') }}</p>
        </div>
        <div >
            <!-- Right side content (logo) -->
            <img src="<?php echo public_path('build/images/logo-lis.png'); ?>" alt="Logo" class="logo">
        </div>
    {{-- </div> --}}
    <div class="info-container" style="float:left;">
        <h2 style="margin-top:0;">Patient Information</h2>
        <p><strong>Name:</strong> John Doe</p>
        <p><strong>Age:</strong> 35</p>
        <p><strong>Gender:</strong> Male</p>
        <p><strong>Sample ID:</strong> 123456</p>
    </div>
    <div class="info-container" style="float:right;">
        <h2 style="margin-top:0;">Report Information</h2>
        <p><strong>Name:</strong> John Doe</p>
        <p><strong>Age:</strong> 35</p>
        <p><strong>Gender:</strong> Male</p>
        <p><strong>Sample ID:</strong> 123456</p>
    </div>
</body>
</html>


{{-- <table>
    <thead>
        <tr>
            <th>Test</th>
            <th>Result</th>
            <th>Reference Range</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Hemoglobin</td>
            <td>13.5 g/dL</td>
            <td>13.0 - 17.0 g/dL</td>
        </tr>
        <tr>
            <td>White Blood Cells</td>
            <td>6,000 /µL</td>
            <td>4,000 - 11,000 /µL</td>
        </tr>
        <tr>
            <td>Platelets</td>
            <td>250,000 /µL</td>
            <td>150,000 - 450,000 /µL</td>
        </tr>
        <tr>
            <td>Red Blood Cells</td>
            <td>4.5 million/µL</td>
            <td>4.0 - 5.5 million/µL</td>
        </tr>
    </tbody>
</table>

<div class="footer">
    <p>Border Life - LIS</p>
</div> --}}
    {{-- <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        thead {
            background-color: #f2f2f2;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <h1>{{ $title }}</h1>
    <p>{{ $date }}</p>
    <br/>
    <br/>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->surname }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html> --}}
