export function dragAndDropModule() {

    return new fineUploader.DragAndDrop({
        dropZoneElements: [document.getElementById('fileView')],
        classes: {
            dropActive: 'blue'
        },
        callbacks: {
            processingDroppedFilesComplete: function (files, dropTarget) {
                fineUploaderBasicInstanceImages().addFiles(files);
            }
        }
    })

};

export function fineUploaderBasicInstanceImages() {
    return new fineUploader.FineUploaderBasic({
        button: document.getElementById('uploadFileButton'),
        request: {
            endpoint: '',
            inputName: 'data-binary',
            customHeaders: {
                "Authorization": "Bearer {{$jwtoken}}"
            }
        },
        callbacks: {
            onComplete: function (id, name, responseJSON) {
                VueInstance.fileUploadComplete(id, name, responseJSON)
            },
            onError: function (id, name, errorReason, XMLHttpRequest) {
                responseJSON = JSON.parse(XMLHttpRequest.response);

                if(responseJSON.errors) {
                    toastr.error(responseJSON.errors[0], responseJSON.message);
                    this.editMode = null;
                    this.editObject = null;
                    return;
                }

            },
            onUpload: function () {
                VueInstance.fileUploadStart();
            },
            onTotalProgress: function (totalUploadedBytes, totalBytes) {
                $('#uploadFileProgrssbar').progress({
                    percent: Math.ceil(totalUploadedBytes / totalBytes * 100)
                });
            },
            onAllComplete: function (succeeded, failed) {
                VueInstance.fileUploadAllComplete(succeeded, failed);
            }
        }
    })
};