<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>DepEd - CDO | Print Slip</title>
    <style>
        @page { size: 8.5in 13in; margin: 0; }

        * { box-sizing: border-box; padding: 0; margin: 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid black; }

        .d-flex { display: flex; }
        .justify-content-center { justify-content: center; }
        .align-items-center { align-items: center; }
        .justify-content-between { justify-content: space-between }
        .text-center { text-align: center; }
        .border { border: 1px solid black; padding: 10px }

        .container { font-size: 8px; display: flex;  height: 100vh; justify-content: center; }
        .section { height: 50vh; width: 100%; padding: 15px; max-width: min-content; }
        .section:first-child { border: 1px solid black;  }
        .header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .trans-code { font-size: 18px; font-weight: 700; }
        .iso td {
            padding: 0 5px;
            text-align: center;
            font-size: 8px
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="section">
            <div class="header">
                <div class="" style="width: 33.33%">
                    <div class="" style="width: fit-content;">
                        <table style="margin-bottom: 5px;">
                            <tr>
                                <td class="text-center trans-code">{{ $detail->document_code }}</td>
                            </tr>
                        </table>
                        <p class="text-center">Document Tracking Code</p>
                    </div>
                </div>
                <div class="text-center" style="width: 33.33%">
                    <img src="{{ asset('assets/images/deped_seal 100x100.png') }}" alt="Deped Seal"
                        style="width: 50px; margin-bottom: 10px;">
                    <p>Republic of the Philippines</p>
                    <p>Department of Education</p>
                    <p>Region X</p>
                </div>
                <div class="iso" style="width: 33.33%">
                    <table>
                        <tr class="odd">
                            <td colspan="3">Document Code No.</td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>SDOCDO-OSDS-REC-DTS-001</strong></td>
                        </tr>
                        <tr class="odd">
                            <td>Rev. No.</td>
                            <td>Effective Date</td>
                            <td>Page No.</td>
                        </tr>
                        <tr>
                            <td>02</td>
                            <td>{{ $detail->created_at->format('m/d/Y') }}</td>
                            <td><strong>1</strong> of <strong>1</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <p class="text-center"><strong>SCHOOLS DIVISION OF CAGAYAN DE ORO CITY</strong></p>
            <hr style="width: 80%; margin: 10px auto;">

            <div class="content">
                <p class="text-center" style="margin: 10px auto;"><strong>DOCUMENT ROUTING SLIP</strong></p>
                <div class="d-flex" style="line-height: 12px; font-size: 9px;">
                    <div style="width: 60%;">
                        <p><strong>FROM:</strong> &nbsp;{{ $detail->name_of_client != ''? $detail->name_of_client : '_______________________________________________' }}</p>
                        <p><strong>SUBJECT:</strong> &nbsp;{{ $detail->description != ''? $detail->description : '____________________________________________' }}</p>
                    </div>
                    <div style="width: 40%;">
                        <p><strong>DATE CREATED:</strong> &nbsp;{{ $detail->created_at->format('m/d/Y') != ''? $detail->created_at->format('m/d/Y') : '__________________' }}</p>
                        <p><strong>CONTACT NO.:</strong> &nbsp;{{ $detail->contact != ''? $detail->contact : '____________________' }}</p>
                    </div>
                </div>
                <div class="border" style="margin: 10px auto">
                    <p><strong>Office/Unit</strong> (<i>To be filled out by Schools Division Superintendent Office</i>) </p>
                    <div class="d-flex" style="margin-top: 10px; font-size: 9px;">
                        <div class="" style="width: 50%">
                            <div class="">[&nbsp; &nbsp;] Asst. Schools Division Supt.</div>
                            <div class="">[&nbsp; &nbsp;] CID, Chief</div>
                            <div class="">[&nbsp; &nbsp;] SGOD, Chief</div>
                            <div class="">[&nbsp; &nbsp;] CID, Public Sch. District Sup.</div>
                            <div class="">[&nbsp; &nbsp;] CID, Educ. program Sup.</div>
                            <div class="">[&nbsp; &nbsp;] Administrative Office</div>
                            <div class="">[&nbsp; &nbsp;] Learning Resource Mngt. Sec</div>
                            <div class="">[&nbsp; &nbsp;] Legal Office</div>
                            <div class="">[&nbsp; &nbsp;] Accounting Office</div>
                            <div class="">[&nbsp; &nbsp;] Budget Office</div>
                            <div class="">[&nbsp; &nbsp;] Supply Office</div>
                        </div>

                        <div class="" style="width: 50%">

                            <div class="">[&nbsp; &nbsp;] SGOD, Engineer / Educ. Facilities</div>
                            <div class="">[&nbsp; &nbsp;] SGOD, Youth Formation Div.</div>
                            <div class="">[&nbsp; &nbsp;] SGOD, Planning and Research</div>
                            <div class="">[&nbsp; &nbsp;] SGOD, Human Resource Dev't</div>
                            <div class="">[&nbsp; &nbsp;] SGOD, Medical / Dental</div>
                            <div class="">[&nbsp; &nbsp;] SGOD, Monitoring & Evaluation</div>
                            <div class="">[&nbsp; &nbsp;] SGOD, Social Mobilization / Net</div>
                            <div class="">[&nbsp; &nbsp;] Info & Communication Tech</div>
                            <div class="">[&nbsp; &nbsp;] COA Office</div>
                            <div class="">[&nbsp; &nbsp;] Human Resource Office</div>
                            <div class="">[&nbsp; &nbsp;] Others: __________</div>
                        </div>
                        {{-- <div class="" style="width: 33.33%">

                        </div> --}}
                    </div>
                    <p style="margin-top: 10px; line-height: 12px; font-size: 9px;">
                        <strong>Please:</strong>_________________________________________________________________________________ <br>
                        _______________________________________________________________________________________
                        _______________________________________________________________________________________
                        _______________________________________________________________________________________
                    </p>

                    <p class="text-center" style="margin-top: 25px; font-size: 9px;">
                        <strong>ROY ANGELO E. GAZO</strong> <br>
                        <small>Schools Division Superintendent</small>
                    </p>
                </div>
            </div>

            <div class="footer" style="margin-bottom: 10px;">
                <div class="d-flex align-items-center">
                    <div class="" style="margin-right: 10px;">
                        <img src="{{ asset('assets/images/depedcdo.png') }}" alt="Deped CDO Logo" style="width: 50px">
                    </div>
                    <div class="">
                        <p><strong>Address:</strong> Fr. Masterson Ave., Upper Balulang, Cagayan de Oro City</p>
                        <p><strong>Tel No.:</strong> (088) 855-0044 | (088) 855-0047 | (088) 328-2142</p>
                        <p><strong>Email Address:</strong> cagayandeoro.city@deped.gov.ph</p>
                        <p><strong>Website:</strong> www.depedcdo.net</p>
                    </div>
                </div>
            </div>

            <div class="customers-slip">
                <hr style="border-style: dashed">
                <div class="" style="margin-top: 10px;">
                    <p class="text-center"><strong>CUSTOMER'S COPY (PLEASE KEEP THIS ALWAYS)</strong></p>
                    <div class="d-flex justify-content-between" style="margin-top: 5px">
                        <div class="" style="line-height: 12px; font-size: 8px;">
                        <p><strong>FROM: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>{{ $detail->name_of_client != ''? $detail->name_of_client : '________________________________________' }}</p>
                        <p><strong>SUBJECT: &nbsp;&nbsp;</strong>{{ $detail->description != ''? $detail->description : '________________________________________' }}</p>
                        <p><strong>DATE: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>{{ $detail->created_at->format('m/d/Y') != ''? $detail->created_at->format('m/d/Y') : '________________________________________' }}</p>
                        </div>
                        <div class="">
                            <table style="margin-bottom: 5px">
                                <tr>
                                    <td class="text-center trans-code">{{ $detail->document_code }}</td>
                                </tr>
                            </table>
                            <p class="text-center">Document Tracking Code</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section" style="display: none;">
        </div>
    </div>


    <script>
        window.print()
    </script>
</body>

</html>
