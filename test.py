import requests
<<<<<<< HEAD
import json
import pdb
import unittest


class AuthTest(unittest.TestCase):
    def test_register(self):
        url = 'http://localhost:8000/api/auth/register/'
        data = {
            'first_name': 'John',
            'last_name': 'Doe',
            'email': 'peter@chiiree.com',
            'password': 'a1a2a3a4a5a6',
            'born': '05/10/2001',
            'identification_card': '28123456',
            'address': 'Exa mple',
            'state': 'Fl con',
            'city': 'Coro'
        }

        response = requests.post(
            url,
            data=json.dumps(data),
            headers={
                'Content-Type': 'application/json'
            }
        )

        print(response.json())
        self.assertEqual(response.status_code, 201)

    def test_login(self):
        url = 'http://localhost:8000/api/auth/login/'
        data = {
            'email': 'peter@chiguire.com',
            'password': 'a1a2a3a4a5a6s'
        }
        response = requests.post(
            url,
            data=json.dumps(data),
            headers={
                'Content-Type': 'application/json'
            }
        )

        self.assertEqual(response.status_code, 200)
        print(response.json())


if __name__ == '__main__':

    # unittest.main()
    AuthTest().test_register()
=======
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

class MessageTest:
    def create():
        url = "http://localhost:8000/api/message/create"

        payload={
            'email': 'chachati28@gmail.com',
            'name': 'Edkar',
            'text': 'Hola Cara de bola'
            }

        headers = {}
        return requests.request("POST", url, headers=headers, data=payload)

    def list():
        url = "http://localhost:8000/api/message/list"

        headers = {}
        return requests.request("GET", url, headers=headers)

    def detail():
        url = "http://localhost:8000/api/message/detail/1"

        headers = {}
        return requests.request("GET", url, headers=headers)



if __name__ == '__main__':
    print(MessageTest.list().text)
    print(MessageTest.detail().text)
    #print(ProgramTest.list().text)
>>>>>>> 55983cd661b95e040f4bb2dc56e0277c6476f438
