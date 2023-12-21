<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST API</title>
</head>
<div id="show">

</div>

<body>


    <script>
        const data = {
            method: "GET"
        }
        fetch('http://localhost/FinalProj/thesis_api', {
                method: "GET"
            })
            .then(res => {
                return res.json()
            })
            .then(data => {
                data.forEach(items => {
                    // console.log(items)
                    console.log('thai_name ', items.thai_name)
                    console.log("member");
                    for (const key in items.author_member) {
                        console.log(`${key} name ${items.author_member[key].name}`);
                    }
                });
            }).catch(error => {
                console.log(error)
            })
    </script>
</body>

</html>