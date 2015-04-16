package com.tonybeltramelli.captcha;

import org.json.simple.JSONObject;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Scanner;

/**
 * @author Tony Beltramelli www.tonybeltramelli.com
 */

public class CustomSimpleCaptcha
{
	private String _challengePath;
	private String[] _questions;
	
	public CustomSimpleCaptcha(String challengePath)
	{
		_challengePath = challengePath;
	}
	
	public String getChallenge() throws IOException
    {
    	String[] questions = _getQuestions();

    	int index = (int) Math.round(Math.random() * (questions.length - 1));

		String challenge = questions[index];
		challenge = challenge.substring(0, challenge.indexOf("="));
		
		JSONObject result = new JSONObject();
		result.put("index", index);
		result.put("challenge", challenge);
		
		return result.toJSONString();
    }
	
	public String checkAnswer(int index, String providedAnswer) throws IOException
	{
		String[] questions = _getQuestions();
		
		if(index >= questions.length) return _getStatusMessage(2);
		
		String expectedAnswer = questions[index];
		expectedAnswer = expectedAnswer.substring(expectedAnswer.indexOf("=") + 1, expectedAnswer.length());

		Boolean isMatched = providedAnswer.toLowerCase().equals(expectedAnswer.toLowerCase());
		
		return _getStatusMessage(isMatched ? 1 : 0);
	}
	
	private String[] _getQuestions() throws IOException
	{
		if(_questions != null) return _questions;
		
		Scanner file = new Scanner(new File(_challengePath)).useDelimiter("\n");
		ArrayList<String> questions = new ArrayList<String>();

		Boolean isFirstLine = true;
		while (file.hasNext())
		{
			if(!isFirstLine)
			{
				questions.add(file.next());
			}else{
				file.next();
				isFirstLine = false;
			}
        }
        file.close();

        _questions = questions.toArray(new String[0]);
        return _questions;
	}
	
	private String _getStatusMessage(int type)
	{
		String status = "error";
		
		switch (type)
		{
			case 0:
				status = "fail";
				break;
			case 1:
				status = "success";
				break;
			default:
				status = "error";
		}
		
		JSONObject result = new JSONObject();
		result.put("status", status);

		return result.toJSONString();
	}
}
