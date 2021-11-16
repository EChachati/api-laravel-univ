import requests
import base64


class AuthTest:
    token = ""
    def __init__(self):
        token = self.login().json()['token']
    def register():
        url = "http://localhost:8000/api/auth/register"

        payload={
            'first_name': 'Edkarrrr',
            'last_name': 'Chachati',
            'email': 'chachati28222228@gmail.com',
            'identification_card': '28123456',
            'born': '05/10/2001',
            'address': 'Coro',
            'state': 'Falcon',
            'city': 'Coro',
            'password': 'vivaelperico'
            }

        files = [('image',('test.jpeg', open('test.jpeg', 'rb'),'image/png'))]
        headers = {}

        response = requests.request("POST", url, headers=headers, data=payload, files=files)

        return response


    def login(payload=None):
        url = "http://localhost:8000/api/auth/login"

        if not payload:

            payload={
            'email': 'chachati28@gmail.com',
            'password': 'vivaelperico'
            }

        headers = {}
        return requests.request("POST", url, headers=headers, data=payload)

    def list():
        url = "http://localhost:8000/api/auth/list"

        headers = {}
        return requests.request("GET", url, headers=headers)


class ProgramTest:
    def list():
        url = "http://localhost:8000/api/program/list"

        headers = {}
        return requests.request("GET", url, headers=headers)



if __name__ == '__main__':
    print(ProgramTest.list().text)