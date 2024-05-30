<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "header.php";
?>
<head>

    <style>
       body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        h1 {
            font-size: 2.5em;
            color: orange;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 1.5em;
            color: orange;
            text-align: left;
            width: 100%;
            margin: 20px 0;
        }
        h4 {
            font-size: 1.2em;
            color: orange;
            text-align: left;
            width: 100%;
            margin: 20px 0;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-body {
            padding: 20px;
        }

        .pManual {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .iconManual {
            margin-right: 10px;
        }

        ul {
            padding-left: 20px;
        }
        li{
            list-style-type: none;
        }

        .admin_text, .user {
            margin-bottom: 20px;
        }

        @media print {
            #downloadButton {
                display: none;
            }
            .container {
                padding: 0;
            }
        }
    </style>

</head>

<body>


    <div id="content" class="container">
        <div class="col">
            <div  class="mt-3">
                <div class="card" id="manual">
                    <div class="card-body">
                        <button id="downloadButton" onclick="generatePDF()" localize="download_info"></button>
                        <h2 class="card-text" localize="pdf"></h2>
                        <p class="pManual">
                            <img src="images/backup.png" alt="backup" width="20" height="20" class="iconManual">
                            <span localize="back_up"></span>
                        </p>
                        <p class="pManual">
                            <img src="images/bin.png" alt="bin" width="20" height="20" class="iconManual">
                            <span localize="delete_q"></span>
                        </p>
                        <p class="pManual">
                            <img src="images/copy.png" alt="copy" width="20" height="20" class="iconManual">
                            <span localize="copy_q"></span>
                        </p>
                        <p class="pManual">
                            <img src="images/edit.png" alt="edit" width="20" height="20" class="iconManual">
                            <span localize="update_q"></span>
                        </p>
                        <p class="pManual">
                            <img src="images/eye.png" alt="eye" width="20" height="20" class="iconManual">
                            <span localize="eye_q"></span>
                        </p>
                        <p class="pManual">
                            <img src="images/qrimage.png" alt="qrimage" width="20" height="20" class="iconManual">
                            <span localize="qrimage_q"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div >

            <div class="col-md6 mx-auto">
                <div class="user mt-3">
                <div class="mt-3">
                    <h2 localize="text_30_i"></h2>
                    <ul>
                        <li localize="text_31_i"></li>
                        <ul>
                            <li localize="text_32_i"></li>
                            <li localize="text_33_i"></li>
                            <li localize="text_34_i"></li>
                        </ul>
                    </ul>
                </div>
                <div class="col">
                    <h2 localize="text_35_i"></h2>
                    <ul>
                        <li localize="text_36_i"></li>
                        <li localize="text_37_i"></li>
                    </ul>
                </div>
                    <div class="title_user">
                        <h1 localize="manual_info"></h1>
                    </div>
                    <h2 localize="login"></h2>
                    <ul>
                        <li localize="text_1"></li>
                    </ul>

                    <h2 localize="change_pwd_i"></h2>
                    <ul>
                        <li localize="text_2"></li>
                    </ul>

                    <h2 localize="vote_i"></h2>
                    <h4 localize="text_3_i"></h4>
                    <ul>
                        <li localize="text_4_i"></li>
                        <li localize="text_5_i"></li>
                        <li localize="text_6_i"></li>
                    </ul>

                    <h2 localize="text_7_i"></h2>
                    <ul>
                        <li localize="text_8_i"></li>
                        <li localize="text_9_i"></li>
                    </ul>

                    <h2 localize="text_10_i"></h2>
                    <ul>
                        <li localize="text_11_i"></li>
                    </ul>

                    <h2 localize="text_12_i"></h2>
                    <ul>
                        <li localize="text_13_i"></li>
                    </ul>

                    <h2 localize="text_14_i"></h2>
                    <ul>
                        <li localize="text_15_i"></li>
                    </ul>

                    <h2 localize="text_16_i"></h2>
                    <ul>
                        <li localize="text_17_i"></li>
                    </ul>

                    <h2 localize="text_18_i"></h2>
                    <ul>
                        <li localize="text_19_i"></li>
                    </ul>

                    <h2 localize="text_20_i"></h2>
                    <ul>
                        <li localize="text_21_i"></li>
                    </ul>

                    <h2 localize="text_22_i"></h2>
                    <ul>
                        <li localize="text_23_i"></li>
                    </ul>

                    <h2 localize="text_24_i"></h2>
                    <ul>
                        <li localize="text_25_i"></li>
                    </ul>
                </div>
                <div class="admin_text mt-3">
                    <h1 localize="text_26_i"></h1>
                    <ol>
                        <li localize="text_27_i"></li>
                        <li localize="text_28_i"></li>
                        <li localize="text_29_i"></li>
                    </ol>
                </div>
                
                
            </div>
        </div>
    </div>
    <script src="script.js"></script>
    <!-- Bootstrap JS and other libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script>
         function generatePDF() {
            const downloadButton = document.getElementById('downloadButton');
            const container = document.querySelector('.container');

            // Save original styles
            const originalStyles = {
                marginTop: container.style.marginTop,
                paddingTop: container.style.paddingTop,
                border: container.style.border,
                borderRadius: container.style.borderRadius,
                boxShadow: container.style.boxShadow
            };

            // Hide the download button and remove the border and styles from the container
            downloadButton.style.display = 'none';
            container.style.marginTop = '0';
            container.style.paddingTop = '0';
            container.style.border = 'none';
            container.style.borderRadius = '0';
            container.style.boxShadow = 'none';

            // Generate the PDF
            const element = document.getElementById('content');
            const options = {
                margin: 1,
                filename: 'manual.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(options).from(element).save().then(() => {
                // Restore the original styles after generating the PDF
                downloadButton.style.display = '';
                container.style.marginTop = originalStyles.marginTop;
                container.style.paddingTop = originalStyles.paddingTop;
                container.style.border = originalStyles.border;
                container.style.borderRadius = originalStyles.borderRadius;
                container.style.boxShadow = originalStyles.boxShadow;
            });
        }
    </script>
</body>

</html>