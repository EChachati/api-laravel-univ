import requests
import base64

url = "http://localhost:8000/api/program/detail/23/"

payload = {}
headers = {
    'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzNjQ4NDEyNSwiZXhwIjoxNjM2NDg3NzI1LCJuYmYiOjE2MzY0ODQxMjUsImp0aSI6Im5BVUVoZUQ2UHF0ckFxRDQiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.qPCcelk-0yIa_lM1ATUPEHT_XfTIwv60trO40Ni-9SA'
}

response = requests.request("GET", url, headers=headers, data=payload)

image = open('test.jpeg', 'wb')
image.write(base64.b64decode(response.json()['image_1']))
image.close()
