<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Form - ACYap Group</title>
    <style>
        @page {
            size: 8.5in 13in;
            margin: 0 0.35in;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Arial", sans-serif;
            font-size: 13px;
            background: #f0f0f0;
            color: #000;
            line-height: 1.4;
        }

        .no-print {
            padding: 20px;
            text-align: center;
            background: #fff;
            border-bottom: 1px solid #ccc;
        }

        .btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .sheet {
            width: 8.5in;
            min-height: 11in;
            background: white;
            margin: 20px auto;
            padding: 0.2in 0.3in;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-section {
            position: relative;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .form-section:not(:last-child) {
            border-bottom: 2px dashed #000;
        }

        /* Top Header Area */
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0px;
        }

        .input-group {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
        }

        .field-line {
            border-bottom: 1px solid #000;
            min-width: 150px;
            text-align: center;
            padding: 0 5px;
            height: 20px;
        }

        .sub-label {
            font-size: 11px;
            margin-top: 2px;
        }

        /* Company Info */
        .company-header {
            margin-bottom: 0px;
        }

        .manager-title {
            font-weight: bold;
            font-size: 14px;
        }

        .company-name {
            font-weight: bold;
            font-size: 14px;
        }

        .underline-input {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 250px;
            padding: 0 5px;
        }

        .body-row {
            margin: 0px 0;
            display: flex;
            align-items: flex-end;
            gap: 10px;
        }

        .reason-box {
            margin: 6px 0;
        }

        .reason-line {
            border-bottom: 1px solid #000;
            width: 100%;
            height: 16px;
            margin-top: 4px;
            display: block;
            text-align: center;
        }

        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 24px;
            margin-top: 6px;
        }

        .sig-block {
            text-align: center;
        }

        .sig-line {
            border-bottom: 1px solid #000;
            width: 100%;
            height: 20px;
            margin-bottom: 2px;
        }

        .hr-section {
            margin-top: 8px;
            font-size: 11px;
        }

        .hr-divider {
            font-weight: bold;
            text-align: left;
            margin-bottom: 4px;
        }

        .leave-table {
            width: 100%;
            border-collapse: collapse;
        }

        .leave-table td {
            padding: 2px 0;
            vertical-align: bottom;
        }

        .short-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 120px;
            margin-left: 5px;
        }

        @media print {
            .no-print { display: none; }
            @page { margin: 0 0.35in 1in 0.35in; }

            html,
            body {
                width: 8.5in;
                height: auto;
                overflow: visible;
                background: white;
                font-size: 11px;
                line-height: 1.15;
            }

            .sheet {
                margin: 0;
                box-shadow: none;
                width: 85%;
                min-height: auto;
                padding: 0.0in 0.0in;
                transform: scale(1.60);
                transform-origin: top left;
            }

            .form-section {
                padding-bottom: 4px !important;
                margin-bottom: 4px !important;
            }

            .header-row,
            .company-header,
            .body-row {
                margin-bottom: 0 !important;
                margin-top: 0 !important;
            }

            .reason-box {
                margin: 4px 0 !important;
            }

            .reason-line {
                height: 14px;
                margin-top: 2px;
            }

            .signature-grid {
                gap: 14px;
                margin-top: 4px;
            }

            .sig-line {
                height: 16px;
                margin-bottom: 1px;
            }

            .sub-label {
                font-size: 9px;
                margin-top: 1px;
            }

            .hr-section {
                margin-top: 4px;
                font-size: 10px;
            }

            .hr-divider {
                margin-bottom: 2px;
            }

            .leave-table td {
                padding: 1px 0;
            }

            .short-line {
                width: 92px;
            }

            .indent-request {
                margin-left: 24px;
            }
        }


        .indent-request { margin-left: 50px; }
        .approved-by-container { border: 1px solid transparent; } /* Placeholder for the green box in image */
    </style>
</head>
<body>
    @php
        $dateFiled = $leaveLog->date_filed ? \Illuminate\Support\Carbon::parse($leaveLog->date_filed)->format('F d, Y') : '';
        $fromDate = $leaveLog->from_date ? \Illuminate\Support\Carbon::parse($leaveLog->from_date)->format('F d, Y') : '';
        $toDate = $leaveLog->to_date ? \Illuminate\Support\Carbon::parse($leaveLog->to_date)->format('F d, Y') : '';
    @endphp

    <div class="no-print">
        <button class="btn" onclick="window.print()">Print Form</button>
    </div>

    <div class="sheet">
        <!-- START COPY 1 -->
        <section class="form-section">
            <div class="header-row">
                <div>
                    Control Number 
                    <span class="underline-input" style="min-width: 100px;">{{ $leaveLog->control_number ?? '' }}</span>
                </div>
                <div class="input-group">
                    <span class="field-line" style="min-width: 100px;">{{ $dateFiled }}</span>
                    <span class="sub-label">Date</span>
                </div>
            </div>

            <div class="company-header">
                <div class="manager-title">The Manager</div>
                <div class="company-name">ACYap Group of Companies</div>
                <div>
                    <span class="underline-input" style="min-width: 100px; margin-left: 25px; margin-top: 5px;">{{ $leaveLog->company ?? ' ' }}</span>
                    
                </div>
            </div>

            <div style="margin-bottom: 0px; margin-top: 10px;">Sir:</div>

            <div class="body-row">
                <span class="indent-request">This is to request a leave of absence from</span>
                <span class="underline-input" style="min-width: 100px;">{{ $fromDate }}</span>
                <span>to</span>
                <span class="underline-input" style="min-width: 100px;">{{ $toDate }}</span>
            </div>

            <div class="reason-box">
                <strong>REASON/S:</strong>
                <span class="reason-line">{{ $leaveLog->reason ?? '' }}</span>
            </div>

            <div style="margin: 0px 0 0px 50px;">
                Hoping for your kind consideration.
            </div>

            <div class="signature-grid">
                <!-- Left Side -->
                <div>
                    <div style="display: flex; align-items: flex-end; margin-bottom: 10px;">
                        <span style="white-space: nowrap; margin-right: 10px;">Relieved by:</span>
                        <div style="width: 100%; text-align: center;">
                            <div class="sig-line" style="font-weight: 700;">{{ $leaveLog->relieved_by ?? '' }}</div>
                        </div>
                    </div>
                    
                    <div>
                        <div style="display: flex; align-items: flex-end;">
                            <span style="white-space: nowrap; margin-right: 10px;">Conformed by:</span>
                            <div style="width: 100%;">
                                <div class="sig-line" style="margin-bottom: 0; text-align: center; font-weight: 700;">{{ $leaveLog->conformed_by ?? '' }}</div>
                            </div>
                        </div>
                        <div class="sub-label" style="margin-left: 80px; text-align: center;">{{ $leaveLog->conformed_by_position ?? '' }}</div>
                    </div>
                </div>

                <!-- Right Side -->
                <div>
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div class="sig-line" style="width: 90%; margin: 0 auto; font-weight: 700;">{{ $leaveLog->employee->full_name ?? '' }}</div>
                        <div class="sub-label">Signature over printed name</div>
                    </div>

                    <div>
                        <div style="display: flex; align-items: flex-end;">
                            <span style="white-space: nowrap; margin-right: 10px;">Approved by:</span>
                            <div style="width: 100%;">
                                <div class="sig-line" style="margin-bottom: 0; text-align: center; font-weight: 700;">{{ $leaveLog->approved_by ?? '' }}</div>
                            </div>
                        </div>
                        <div class="sub-label" style="margin-left: 73px; text-align: center;">{{ $leaveLog->approved_by_position ?? '' }}</div>
                    </div>
                </div>
            </div>

            <div class="hr-section">
                <div class="hr-divider">*****to be filled up by Human Resource Department Only*****</div>
                
                <table class="leave-table">
                    <tr>
                        <td style="width: 25%;">REMAINING LEAVE:</td>
                        <td style="width: 75%;">AVAILED LEAVE:</td>
                    </tr>
                </table>

                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <div style="display: flex; gap: 20px;">
                        <div>VL <span class="short-line"></span></div>
                        <div>VL: <span class="short-line"></span></div>
                    </div>
                    <div style="display: flex; gap: 20px;">
                        <div>W/O PAY <span class="short-line"></span></div>
                        <div>SL <span class="short-line" style="width: 200px;"></span></div>
                        <div>SSS SL <span class="short-line"></span></div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <div>SL <span class="short-line"></span></div>
                    <div style="display: flex; gap: 20px;">
                        <div>Sig <span class="short-line" style="width: 250px;"></span></div>
                        <div>Date <span class="short-line"></span></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END COPY 1 -->
        
        <!-- START COPY 2 -->
        <section class="form-section">
            <div class="header-row">
                <div>
                    Control Number 
                    <span class="underline-input" style="min-width: 100px;">{{ $leaveLog->control_number ?? '' }}</span>
                </div>
                <div class="input-group">
                    <span class="field-line" style="min-width: 100px;">{{ $dateFiled }}</span>
                    <span class="sub-label">Date</span>
                </div>
            </div>

            <div class="company-header">
                <div class="manager-title">The Manager</div>
                <div class="company-name">ACYap Group of Companies</div>
                <div>
                    <span class="underline-input" style="min-width: 100px; margin-left: 25px; margin-top: 5px;">{{ $leaveLog->company ?? ' ' }}</span>
                    
                </div>
            </div>

            <div style="margin-bottom: 0px; margin-top: 10px;">Sir:</div>

            <div class="body-row">
                <span class="indent-request">This is to request a leave of absence from</span>
                <span class="underline-input" style="min-width: 100px;">{{ $fromDate }}</span>
                <span>to</span>
                <span class="underline-input" style="min-width: 100px;">{{ $toDate }}</span>
            </div>

            <div class="reason-box">
                <strong>REASON/S:</strong>
                <span class="reason-line">{{ $leaveLog->reason ?? '' }}</span>
            </div>

            <div style="margin: 0px 0 0px 50px;">
                Hoping for your kind consideration.
            </div>

            <div class="signature-grid">
                <!-- Left Side -->
                <div>
                    <div style="display: flex; align-items: flex-end; margin-bottom: 10px;">
                        <span style="white-space: nowrap; margin-right: 10px;">Relieved by:</span>
                        <div style="width: 100%; text-align: center;">
                            <div class="sig-line" style="font-weight: 700;">{{ $leaveLog->relieved_by ?? '' }}</div>
                        </div>
                    </div>
                    
                    <div>
                        <div style="display: flex; align-items: flex-end;">
                            <span style="white-space: nowrap; margin-right: 10px;">Conformed by:</span>
                            <div style="width: 100%;">
                                <div class="sig-line" style="margin-bottom: 0; text-align: center; font-weight: 700;">{{ $leaveLog->conformed_by ?? '' }}</div>
                            </div>
                        </div>
                        <div class="sub-label" style="margin-left: 80px; text-align: center;">{{ $leaveLog->conformed_by_position ?? '' }}</div>
                    </div>
                </div>

                <!-- Right Side -->
                <div>
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div class="sig-line" style="width: 90%; margin: 0 auto; font-weight: 700;">{{ $leaveLog->employee->full_name ?? '' }}</div>
                        <div class="sub-label">Signature over printed name</div>
                    </div>

                    <div>
                        <div style="display: flex; align-items: flex-end;">
                            <span style="white-space: nowrap; margin-right: 10px;">Approved by:</span>
                            <div style="width: 100%;">
                                <div class="sig-line" style="margin-bottom: 0; text-align: center; font-weight: 700;">{{ $leaveLog->approved_by ?? '' }}</div>
                            </div>
                        </div>
                        <div class="sub-label" style="margin-left: 73px; text-align: center;">{{ $leaveLog->approved_by_position ?? '' }}</div>
                    </div>
                </div>
            </div>

            <div class="hr-section">
                <div class="hr-divider">*****to be filled up by Human Resource Department Only*****</div>
                
                <table class="leave-table">
                    <tr>
                        <td style="width: 25%;">REMAINING LEAVE:</td>
                        <td style="width: 75%;">AVAILED LEAVE:</td>
                    </tr>
                </table>

                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <div style="display: flex; gap: 20px;">
                        <div>VL <span class="short-line"></span></div>
                        <div>VL: <span class="short-line"></span></div>
                    </div>
                    <div style="display: flex; gap: 20px;">
                        <div>W/O PAY <span class="short-line"></span></div>
                        <div>SL <span class="short-line" style="width: 200px;"></span></div>
                        <div>SSS SL <span class="short-line"></span></div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <div>SL <span class="short-line"></span></div>
                    <div style="display: flex; gap: 20px;">
                        <div>Sig <span class="short-line" style="width: 250px;"></span></div>
                        <div>Date <span class="short-line"></span></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END COPY 2 -->

        <!-- START COPY 3 -->
        <section class="form-section">
            <div class="header-row">
                <div>
                    Control Number 
                    <span class="underline-input" style="min-width: 100px;">{{ $leaveLog->control_number ?? '' }}</span>
                </div>
                <div class="input-group">
                    <span class="field-line" style="min-width: 100px;">{{ $dateFiled }}</span>
                    <span class="sub-label">Date</span>
                </div>
            </div>

            <div class="company-header">
                <div class="manager-title">The Manager</div>
                <div class="company-name">ACYap Group of Companies</div>
                <div>
                    <span class="underline-input" style="min-width: 100px; margin-left: 25px; margin-top: 5px;">{{ $leaveLog->company ?? ' ' }}</span>
                    
                </div>
            </div>

            <div style="margin-bottom: 0px; margin-top: 10px;">Sir:</div>

            <div class="body-row">
                <span class="indent-request">This is to request a leave of absence from</span>
                <span class="underline-input" style="min-width: 100px;">{{ $fromDate }}</span>
                <span>to</span>
                <span class="underline-input" style="min-width: 100px;">{{ $toDate }}</span>
            </div>

            <div class="reason-box">
                <strong>REASON/S:</strong>
                <span class="reason-line">{{ $leaveLog->reason ?? '' }}</span>
            </div>

            <div style="margin: 0px 0 0px 50px;">
                Hoping for your kind consideration.
            </div>

            <div class="signature-grid">
                <!-- Left Side -->
                <div>
                    <div style="display: flex; align-items: flex-end; margin-bottom: 10px;">
                        <span style="white-space: nowrap; margin-right: 10px;">Relieved by:</span>
                        <div style="width: 100%; text-align: center;">
                            <div class="sig-line" style="font-weight: 700;">{{ $leaveLog->relieved_by ?? '' }}</div>
                        </div>
                    </div>
                    
                    <div>
                        <div style="display: flex; align-items: flex-end;">
                            <span style="white-space: nowrap; margin-right: 10px;">Conformed by:</span>
                            <div style="width: 100%;">
                                <div class="sig-line" style="margin-bottom: 0; text-align: center; font-weight: 700;">{{ $leaveLog->conformed_by ?? '' }}</div>
                            </div>
                        </div>
                        <div class="sub-label" style="margin-left: 80px; text-align: center;">{{ $leaveLog->conformed_by_position ?? '' }}</div>
                    </div>
                </div>

                <!-- Right Side -->
                <div>
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div class="sig-line" style="width: 90%; margin: 0 auto; font-weight: 700;">{{ $leaveLog->employee->full_name ?? '' }}</div>
                        <div class="sub-label">Signature over printed name</div>
                    </div>

                    <div>
                        <div style="display: flex; align-items: flex-end;">
                            <span style="white-space: nowrap; margin-right: 10px;">Approved by:</span>
                            <div style="width: 100%;">
                                <div class="sig-line" style="margin-bottom: 0; text-align: center; font-weight: 700;">{{ $leaveLog->approved_by ?? '' }}</div>
                            </div>
                        </div>
                        <div class="sub-label" style="margin-left: 73px; text-align: center;">{{ $leaveLog->approved_by_position ?? '' }}</div>
                    </div>
                </div>
            </div>

            <div class="hr-section">
                <div class="hr-divider">*****to be filled up by Human Resource Department Only*****</div>
                
                <table class="leave-table">
                    <tr>
                        <td style="width: 25%;">REMAINING LEAVE:</td>
                        <td style="width: 75%;">AVAILED LEAVE:</td>
                    </tr>
                </table>

                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <div style="display: flex; gap: 20px;">
                        <div>VL <span class="short-line"></span></div>
                        <div>VL: <span class="short-line"></span></div>
                    </div>
                    <div style="display: flex; gap: 20px;">
                        <div>W/O PAY <span class="short-line"></span></div>
                        <div>SL <span class="short-line" style="width: 200px;"></span></div>
                        <div>SSS SL <span class="short-line"></span></div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <div>SL <span class="short-line"></span></div>
                    <div style="display: flex; gap: 20px;">
                        <div>Sig <span class="short-line" style="width: 250px;"></span></div>
                        <div>Date <span class="short-line"></span></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END COPY 3 -->

    </div>

</body>
</html>