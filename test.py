import requests
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
