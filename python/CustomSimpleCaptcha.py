__author__ = 'Tony Beltramelli - http://www.tonybeltramelli.com'

from random import randint
import json


class CustomSimpleCaptcha:
    _challenge_path = None
    _questions = None

    def __init__(self, challenge_path):
        self._challenge_path = challenge_path

    def get_challenge(self):
        questions = self._get_questions()

        index = randint(0, len(questions) - 1)

        challenge = questions[index]
        challenge = challenge[:challenge.index("=")]

        return json.dumps({'index': index, 'challenge': challenge})

    def check_answer(self, index, provided_answer):
        questions = self._get_questions()

        if (not isinstance(index, int)) or (index >= len(questions)):
            return self._get_status_message(2)

        expected_answer = questions[index]
        expected_answer = expected_answer[expected_answer.index("=") + 1:]

        is_matched = provided_answer.lower() == expected_answer.lower()

        return self._get_status_message(is_matched)

    def _get_questions(self):
        if self._questions is not None:
            return self._questions

        questions = open(self._challenge_path, "r")
        self._questions = questions.read().split("\n")
        self._questions = self._questions[1:len(self._questions)]

        return self._questions

    def _get_status_message(self, t):
        status = "error"

        if t == 0:
            status = "fail"
        elif t == 1:
            status = "success"

        return json.dumps({"status": status})
