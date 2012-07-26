<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>RapidNewProject - MODX Project Kickstarter by Mark Hamstra</title>
</head>
<body>
    <h1>RapidNewProject</h1>
    <form action="{base_url}" method="post">
        <p class="label">Choose MODX Installation:</p>
        {modx_root_input}

        <p><label for="namespace">Enter the Projects' Namespace</label></p>
        <input type="text" id="namespace" name="namespace" value="{namespace}" />


        <p><label for="target_url">Enter the Projects' Target URL</label></p>
        <input type="text" id="target_url" name="target_url" value="{target_url}" />


        <p><label for="target_path">Enter the Projects' Target Path</label></p>
        <input type="text" id="target_path" name="target_path" value="{target_path}" />

        <input type="submit" id="submit" name="rnp_submit" value="{rnp_submit}" />


    </form>
</body>
</html>
