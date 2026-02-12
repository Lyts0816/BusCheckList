<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dispatched Trips Report</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 10px;
            color: #333;
        }
        
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .logo {
            width: 100px;
            height: auto;
        }
        
        .header-text {
            flex: 1;
            text-align: center;
            padding: 0 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        
        .header p {
            margin: 3px 0;
            font-size: 10px;
        }
        
        .report-date {
            text-align: right;
            margin-bottom: 10px;
            font-size: 8px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        
        table thead {
            background-color: #1a365d;
            color: white;
        }
        
        table th {
            border: 1px solid #999;
            padding: 4px;
            text-align: left;
            font-weight: bold;
            font-size: 7px;
            word-wrap: break-word;
        }
        
        table td {
            border: 1px solid #ddd;
            padding: 3px;
            font-size: 7px;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('images/yellow-bus-line-logo.png') }}" alt="Yellow Bus Line" style="max-width: 100%; height: auto;">
        </div>
        <div class="header-text">
            <h1>DISPATCHED TRIPS REPORT</h1>
            <p>"We go the distance to serve you."</p>
        </div>
    </div>
    
    <div class="report-date">
        <strong>Report Date:</strong> {{ $exportDate }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Trip #</th>
                <th>Dispatch Date</th>
                <th>Route From</th>
                <th>Route To</th>
                <th>KM Run</th>
                <th>Nature of Trip</th>
                <th>Bus #</th>
                <th>Class</th>
                <th>Driver</th>
                <th>Conductor</th>
                <th>Time in Terminal</th>
                <th>Time of Parking</th>
                <th>Time of Arrival</th>
                <th>Time of Departure</th>
                <th>Idle Start</th>
                <th>Idle End</th>
                <th>Travel Time</th>
                <th>Add Time</th>
                <th>Ticket #</th>
                <th>Passengers</th>
                <th>Baggage Amt</th>
                <th>Baggage Ticket #</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trips as $trip)
                @php
                    // Format total travel time
                    $travelTimeFormatted = 'N/A';
                    if ($trip->total_travel_time_minutes) {
                        $hours = intdiv($trip->total_travel_time_minutes, 60);
                        $minutes = $trip->total_travel_time_minutes % 60;
                        $travelTimeFormatted = "{$hours}h {$minutes}m";
                    }
                    
                    // Format total add time
                    $addTimeFormatted = 'N/A';
                    if ($trip->total_add_time_minutes) {
                        $addHours = intdiv($trip->total_add_time_minutes, 60);
                        $addMinutes = $trip->total_add_time_minutes % 60;
                        $addTimeFormatted = "{$addHours}h {$addMinutes}m";
                    }
                    
                    // Format baggage amount
                    $baggageAmount = $trip->baggage_amount ? number_format($trip->baggage_amount, 2) : 'N/A';
                @endphp
                <tr>
                    <td class="text-center">{{ $trip->trip_number ?? 'N/A' }}</td>
                    <td class="text-center">{{ $trip->dispatchSheet?->dispatch_date ? date('M d, Y', strtotime($trip->dispatchSheet->dispatch_date)) : 'N/A' }}</td>
                    <td>{{ $trip->dispatchSheet?->route?->from ?? 'N/A' }}</td>
                    <td>{{ $trip->dispatchSheet?->route?->to ?? 'N/A' }}</td>
                    <td class="text-center">{{ $trip->dispatchSheet?->distance_at_dispatch ? $trip->dispatchSheet->distance_at_dispatch . ' km' : 'N/A' }}</td>
                    <td>{{ $trip->natureOfTrip?->nature_of_trip_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $trip->busNumber?->bus_number ?? 'N/A' }}</td>
                    <td class="text-center">{{ $trip->busNumber?->bus_class ?? 'N/A' }}</td>
                    <td>{{ $trip->snap_drivers ?? 'N/A' }}</td>
                    <td>{{ $trip->snap_conductors ?? 'N/A' }}</td>
                    <td class="text-center">{{ $trip->time_in_terminal ? $trip->time_in_terminal->format('h:i A') : 'N/A' }}</td>
                    <td class="text-center">{{ $trip->time_of_parking ? $trip->time_of_parking->format('h:i A') : 'N/A' }}</td>
                    <td class="text-center">{{ $trip->time_of_arrival ? $trip->time_of_arrival->format('h:i A') : 'N/A' }}</td>
                    <td class="text-center">{{ $trip->time_of_departure ? $trip->time_of_departure->format('h:i A') : 'N/A' }}</td>
                    <td class="text-center">{{ $trip->idle_time_start ? $trip->idle_time_start->format('h:i A') : 'N/A' }}</td>
                    <td class="text-center">{{ $trip->idle_time_end ? $trip->idle_time_end->format('h:i A') : 'N/A' }}</td>
                    <td class="text-center">{{ $travelTimeFormatted }}</td>
                    <td class="text-center">{{ $addTimeFormatted }}</td>
                    <td class="text-center">{{ $trip->ticket_number ?? 'N/A' }}</td>
                    <td class="text-center">{{ $trip->passengers_on_board ?? 'N/A' }}</td>
                    <td class="text-center">{{ $baggageAmount }}</td>
                    <td class="text-center">{{ $trip->baggage_ticket_no ?? 'N/A' }}</td>
                    <td style="font-size: 7px;">{{ Str::limit($trip->remarks ?? 'N/A', 15) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="23" class="text-center" style="padding: 15px;">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>
</body>
</html>
