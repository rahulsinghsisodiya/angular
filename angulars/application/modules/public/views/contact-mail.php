<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title></title>
    </head>
    <body>

        <table style="border:1px solid black;width:100%;margin:0px auto;font-size:14px;font-family:calibri;">
            <thead style="background-color:blue;">
                <tr>
                    <th colspan="2" style="padding-left:20px; font-size:18px;"><?php echo $subject; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="padding-left:20px;">Name</th>
                    <td><?php echo $from_name ?>'</td>
                </tr>
                <tr>
                    <th style="padding-left:20px;">Email</th>
                    <td><?php echo $from_email ?></td>
                </tr>
                <tr>
                    <th style="padding-left:20px;">Message</th>
                    <td><?php echo $message ?></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>    
