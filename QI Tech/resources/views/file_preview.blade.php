<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        #previewContainer {
            width: 80%;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        iframe {
            width: 100%;
            height: 600px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        canvas {
            display: block;
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .alert {
            color: #d9534f;
            font-size: 18px;
            text-align: center;
            padding: 20px;
            background-color: #f2dede;
            border: 1px solid #d9534f;
            border-radius: 8px;
        }

        .loading {
            text-align: center;
            font-size: 18px;
            color: #777;
        }

        .buttons {
            margin-top: 20px;
            text-align: center;
        }

        .buttons button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .buttons button:hover {
            background-color: #4cae4c;
        }

        .buttons button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
</head>
<body>
    <h1>File Preview</h1>

    <div id="previewContainer"></div>

    <div class="buttons">
        <button id="printButton" onclick="printFile()">Print</button>
        <button id="downloadButton" onclick="downloadFile()">Download</button>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aspose.slides/23.10.0/aspose.slides.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <script>
        var fileExtension = '{{ $fileExtension }}'; // e.g., 'pdf', 'docx', 'xlsx', etc.
        var path = '{{ $path }}'; // The URL or path to the file
    
        if (fileExtension == 'pdf') {
            // PDF Preview using iframe
            var container = document.getElementById('previewContainer');
            
            // Create an iframe element
            var iframe = document.createElement('iframe');
            iframe.src = path;  // Set the PDF URL as the iframe source
            iframe.width = '100%';  // Set the width to 100%
            iframe.height = '600px';  // Set a fixed height for the iframe
            iframe.frameBorder = '0';  // Remove the border around the iframe
            
            // Append the iframe to the container
            container.innerHTML = '';  // Clear any previous content
            container.appendChild(iframe);
            
        } else if (fileExtension == 'docx') {
            // DOCX Preview using Mammoth.js
            var container = document.getElementById('previewContainer');
            var xhr = new XMLHttpRequest();
            xhr.open('GET', path, true);
            xhr.responseType = 'arraybuffer';
    
            xhr.onload = function() {
                var arrayBuffer = xhr.response;
                mammoth.convertToHtml({ arrayBuffer: arrayBuffer })
                    .then(function(result) {
                        container.innerHTML = result.value;
                    })
                    .catch(function(err) {
                        console.error('Error converting docx:', err);
                    });
            };
    
            xhr.send();
    
        } else if (fileExtension == 'xlsx') {
            // XLSX Preview using SheetJS (xlsx)
            var container = document.getElementById('previewContainer');
            
            var xhr = new XMLHttpRequest();
            xhr.open('GET', path, true);
            xhr.responseType = 'arraybuffer';
    
            xhr.onload = function() {
                var arrayBuffer = xhr.response;
                var workbook = XLSX.read(arrayBuffer, { type: 'array' });
    
                var sheetNames = workbook.SheetNames;
                var sheetContainer = document.createElement('div');
                sheetContainer.style.display = 'flex';
                sheetContainer.style.flexDirection = 'column';
    
                // Loop through each sheet in the workbook
                sheetNames.forEach(function(sheetName) {
                    var sheet = workbook.Sheets[sheetName];
                    var htmlString = XLSX.utils.sheet_to_html(sheet);  // Convert sheet to HTML
    
                    var sheetElement = document.createElement('div');
                    sheetElement.classList.add('xlsx-sheet');
                    sheetElement.style.margin = '10px 0';
                    sheetElement.innerHTML = htmlString; // Add the sheet HTML to the container
    
                    sheetContainer.appendChild(sheetElement);
                });
    
                container.innerHTML = '';  // Clear any previous content
                container.appendChild(sheetContainer);
            };
    
            xhr.send();
    
        } else if (fileExtension == 'jpg' || fileExtension == 'jpeg' || fileExtension == 'png' || fileExtension == 'gif') {
            // Image Preview (supporting common image formats)
            var container = document.getElementById('previewContainer');
            
            // Create an img element and set the source to the file path
            var img = document.createElement('img');
            img.src = path;
            img.alt = 'Image Preview';
            img.style.maxWidth = '100%';  // Make image responsive
            img.style.maxHeight = '600px';  // Limit the height of the image
            
            // Append the image to the container
            container.innerHTML = '';  // Clear any previous content
            container.appendChild(img);
    
        } else if (fileExtension == 'mp4' || fileExtension == 'webm' || fileExtension == 'ogg') {
            // Video Preview (supporting common video formats)
            var container = document.getElementById('previewContainer');
            
            // Create a video element and set the source to the file path
            var video = document.createElement('video');
            video.src = path;
            video.controls = true;  // Add controls for play, pause, etc.
            video.width = '100%';    // Set video width to 100%
            video.height = 'auto';   // Maintain aspect ratio
            
            // Append the video to the container
            container.innerHTML = '';  // Clear any previous content
            container.appendChild(video);
            
        } else if (fileExtension == 'txt') {
            // TXT Preview (Plain Text File)
            var container = document.getElementById('previewContainer');
            
            var xhr = new XMLHttpRequest();
            xhr.open('GET', path, true);
            xhr.responseType = 'text';
    
            xhr.onload = function() {
                var textContent = xhr.response;
                container.innerHTML = '<p style="white-space: pre-wrap;">' + textContent + '</p>';
            };
    
            xhr.send();
    
        }else if (fileExtension == 'pptx') {
        // Handle PPTX file - Download directly
        var container = document.getElementById('previewContainer');
        container.innerHTML = `
            <div class="alert">
                PPTX files are not supported for preview. Click the button below to download the file.
            </div>
        `;
        
        // Add a download button for PPTX
        // var downloadButton = document.createElement('button');
        // downloadButton.textContent = 'Download PPTX';
        // downloadButton.style.marginTop = '10px';
        // downloadButton.style.backgroundColor = '#5bc0de';
        // downloadButton.style.color = '#fff';
        // downloadButton.style.border = 'none';
        // downloadButton.style.borderRadius = '5px';
        // downloadButton.style.padding = '10px 20px';
        // downloadButton.style.cursor = 'pointer';
        
        // downloadButton.onclick = function() {
        //     var link = document.createElement('a');
        //     link.href = path;
        //     link.download = path.split('/').pop(); // Extract the file name
        //     link.click();
        // };

        container.appendChild(downloadButton);
        } else{
            // If the file type is not supported for preview
            document.getElementById('previewContainer').innerHTML = "<p>File type not supported for preview.</p>";
        }
    
        // Function to print the file content
        function printFile() {
            var content = document.getElementById('previewContainer').innerHTML;
            var printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write('<html><head><title>Print</title></head><body>' + content + '</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    
        // Function to download the file
        function downloadFile() {
            var link = document.createElement('a');
            link.href = path;
            link.download = path.split('/').pop(); // Get the file name from the URL
            link.click();
        }
    </script>
    
    
    

</body>
</html>
