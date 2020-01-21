<div style="border: solid black 2px;">
    <div style="padding: 20px;">
        <h1>Upload M1 export file of products</h1>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            Select file to upload:
            <input type="file" name="export" id="export">
            <input type="submit" value="Submit" name="submit">
        </form>
    </div>
</div>
<br>
<br>
<div style="border: solid black 2px;">
    <div style="padding: 20px;">
        <h1>Upload a configurable product file</h1>
        <h4>This process will automagically separate an attribute set file with multiple configurable products, such as, Hanging Structures.</h4>
        <form action="upload.php" method="post" enctype="multipart/form-data" >
            Select file to upload:
            <input type="file" name="config-file" id="config-file">
            <input type="submit" value="Submit" name="submit">
        </form>
    </div>
</div>