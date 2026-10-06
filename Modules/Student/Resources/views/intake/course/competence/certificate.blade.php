@extends('user::layouts.master')
@section('title', 'Admin | Certificate Preview')

@section('content')
<!-- Content Header (Page header) -->
<style>
    #previewContainer {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    /* Ensure page breaks are visually shown in the Fabric.js preview */
    .page-break {
        page-break-before: always;
        display: block;
        width: 100%;
        height: 20px;
        background: transparent;
    }
</style>

<div class="container">
    <h2 class="mb-4">Preview of {{$newTemplateName}}</h2>
    <div class="card">
        <div class="card-header">
            <button type="submit" id="print" class="btn btn-primary">Print</button>
        </div>
        <div class="card-body">
            <div id="previewContainer">
                {!! $htmlContentNew !!}
            </div>
        </div>
    </div>
    <a href="{{ route('admin.student.intake.competence.index', $id) }}" class="btn btn-primary mt-3">Back to Editor</a>
</div>
@endsection

@section('scripts')
<!-- Include jsPDF and html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    const templateName = "{{$newTemplateName}}";
    const studentName = "{{ userName('Student', $studentIntakeCourse->student_id) }}";
    const fileName = templateName + ".pdf";

//     document.getElementById("print").addEventListener("click", async function () {
//     console.log("Print button clicked");

//     const { jsPDF } = window.jspdf;
//     let pdf = new jsPDF('p', 'mm', 'a4');

//     let previewContainer = document.getElementById("previewContainer");
//     let pageWidth = 210; // A4 width in mm
//     let pageHeight = 297; // A4 height in mm
//     let padding = 10; // Padding in mm
//     let maxContentHeight = pageHeight - (2 * padding); // Maximum content height per page

//     let currentY = padding;

//     // Capture all content (before, table, after)
//     let allElements = Array.from(previewContainer.children);

//     for (let i = 0; i < allElements.length; i++) {
//         let element = allElements[i];

//         // **Detect Page Breaks and Start a New Page**
//         if (element.classList.contains("page-break")) {
//             console.log("Page break detected, adding a new page...");
//             pdf.addPage();
//             currentY = padding;
//             continue;
//         }

//         // **Handle the Table Separately**
//         if (element.tagName.toLowerCase() === "table") {
//             let table = element;
//             let rows = table.rows;
//             let tableHeaders = rows[0]; // Get headers separately

//             // Render table headers once at the start
//             let headerCanvas = await html2canvas(tableHeaders, { scale: 2, useCORS: true, allowTaint: true });
//             let headerImg = headerCanvas.toDataURL("image/png");
//             let imgWidth = pageWidth - (2 * padding);
//             let headerHeight = (headerCanvas.height * imgWidth) / headerCanvas.width;

//             pdf.addImage(headerImg, 'PNG', padding, currentY, imgWidth, headerHeight);
//             currentY += headerHeight + 5; // Move cursor down

//             for (let j = 1; j < rows.length; j++) {
//                 let row = rows[j];

//                 try {
//                     let canvas = await html2canvas(row, { scale: 2, useCORS: true, allowTaint: true });
//                     let imgData = canvas.toDataURL("image/png");
//                     let imgHeight = (canvas.height * imgWidth) / canvas.width;

//                     // **Check if row fits, else move to next page**
//                     if (currentY + imgHeight > maxContentHeight) {
//                         pdf.addPage();
//                         currentY = padding;

//                         // Re-add table headers on new page
//                         pdf.addImage(headerImg, 'PNG', padding, currentY, imgWidth, headerHeight);
//                         currentY += headerHeight + 5;
//                     }

//                     pdf.addImage(imgData, 'PNG', padding, currentY, imgWidth, imgHeight);
//                     currentY += imgHeight + 5; // Move cursor down for next row
//                 } catch (error) {
//                     console.error("Error rendering row:", error);
//                 }
//             }
//             continue; // Move to next element
//         }

//         // **Render Other Content (Text, Images, etc.)**
//         try {
//             let canvas = await html2canvas(element, { scale: 2, useCORS: true, allowTaint: true });
//             let imgData = canvas.toDataURL("image/png");
//             let imgWidth = pageWidth - (2 * padding);
//             let imgHeight = (canvas.height * imgWidth) / canvas.width;

//             // **Check if content fits, else move to next page**
//             if (currentY + imgHeight > maxContentHeight) {
//                 pdf.addPage();
//                 currentY = padding;
//             }

//             pdf.addImage(imgData, 'PNG', padding, currentY, imgWidth, imgHeight);
//             currentY += imgHeight + 5;
//         } catch (error) {
//             console.error("Error rendering content:", error);
//         }
//     }

//     pdf.save(`${templateName}.pdf`);
// });




    //working 
        document.getElementById("print").addEventListener("click", function () {
        console.log("Print button clicked");

        const { jsPDF } = window.jspdf;
        let pdf = new jsPDF('p', 'mm', 'a4');

        let previewContainer = document.getElementById("previewContainer");

        html2canvas(previewContainer, {
            scale: 2.8,
            useCORS: true
        }).then(canvas => {
            let imgData = canvas.toDataURL("image/png");

            let pageWidth = 210; // A4 width in mm
            let pageHeight = 297; // A4 height in mm
            let padding = 10; // Padding in mm
            let maxContentHeight = pageHeight - (2 * padding); // Maximum content height per page

            let imgWidth = pageWidth - (2 * padding); // Adjust width with padding
            let imgHeight = (canvas.height * imgWidth) / canvas.width; // Maintain aspect ratio

            let currentY = padding; // Start position on first page

            // If the content fits on one page, print normally
            if (imgHeight <= maxContentHeight) {
                pdf.addImage(imgData, 'PNG', padding, padding, imgWidth, imgHeight);
            } else {
                let totalPages = Math.ceil(imgHeight / maxContentHeight); // Calculate total pages
                let remainingHeight = imgHeight;
                let cropStartY = 0; // Y position for cropping the canvas

                for (let i = 0; i < totalPages; i++) {
                    let canvasSection = document.createElement("canvas");
                    let ctx = canvasSection.getContext("2d");

                    // Set canvas section dimensions
                    canvasSection.width = canvas.width;
                    canvasSection.height = maxContentHeight * (canvas.width / imgWidth);

                    // Copy portion of the original canvas
                    ctx.drawImage(canvas, 0, cropStartY, canvas.width, canvasSection.height, 0, 0, canvas.width, canvasSection.height);

                    let sectionImgData = canvasSection.toDataURL("image/png");

                    // Add section image to PDF
                    pdf.addImage(sectionImgData, 'PNG', padding, padding, imgWidth, maxContentHeight);

                    cropStartY += canvasSection.height; // Move crop position down

                    remainingHeight -= maxContentHeight;
                    if (remainingHeight > 0) pdf.addPage(); // Add new page if more content remains
                }
            }

            pdf.save(`${templateName}.pdf`); // Save file with dynamic name
        });
    });




    // document.getElementById("print").addEventListener("click", function () {
    //     console.log("print");
    //     const { jsPDF } = window.jspdf;
    //     let pdf = new jsPDF('p', 'mm', 'a4');

    //     let previewContainer = document.getElementById("previewContainer");

    //     html2canvas(previewContainer, { scale: 2 }).then(canvas => {
    //         let imgData = canvas.toDataURL("image/png");

    //         let pageWidth = 210; // A4 width in mm
    //         let pageHeight = 297; // A4 height in mm
    //         let padding = 15; // Padding in mm

    //         let imgWidth = pageWidth - (2 * padding); // Adjust width with padding
    //         let imgHeight = (canvas.height * imgWidth) / canvas.width; // Maintain aspect ratio

    //         if (imgHeight > (pageHeight - (2 * padding))) {
    //             imgHeight = pageHeight - (2 * padding); // Ensure content fits within page
    //         }

    //         pdf.addImage(imgData, 'PNG', padding, padding, imgWidth, imgHeight);
    //         pdf.save(fileName); // Download PDF
    //     });
    // });
</script>
@endsection