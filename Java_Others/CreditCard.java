import java.util.Scanner;

public class CreditCard
{
	public static void main(String[] args)
	{
		Scanner input = new Scanner(System.in);
  		long number;
  		do
  		{
   			System.out.print("Enter a credit card number or 0 to stop program: ");
   			number = input.nextLong();
   			if (number != 0)
   			{
				if (isValid(number))
				{
				 	System.out.println(number + " is valid.");
				}
				else
				{
				 	System.out.println(number + " is invalid.");
				}
			 }

		} while (number != 0);

	}


	public static boolean isValid(long number)
	{
		int fp = (int) getPrefix(number, 1);
		if (fp != 4 && fp != 5 && fp != 3 && fp != 6)
		{
			return false;
		}
		if (fp == 3)
		{
			int sp = (int) getPrefix(number, 2);
			if (sp != 37)
			{
				return false;
			}
		}

		if ((sumOfDoubleEvenPlace(number) + sumOfOddPlace(number)) % 10 != 0)
		{
			return false;
		}
		else
		{
			return true;
		}

	}
	public static int sumOfDoubleEvenPlace(long number)
	{
		int num1 = getSize(number);
		int total = 0;
		for (int i = 2; i <= num1; i += 2)
		{
			total += getDigit(2 * getDigitFromIndex(number, i));
		}

			  return total;

	}
	public static int getDigitFromIndex(long number, int index)
	{
		int digit = (int) (((number / Math.pow(10, index - 1))) % 10);

		return digit;
	}
	public static int getDigit(int number)
	{
		int d = number / 10;
		int s = number % 10;
		if (d == 0)
		{
			return number;
		}
		else
		{
			return d + s;
		}

	}
	public static int sumOfOddPlace(long number)
	{
		int p = getSize(number);
		int total = 0;
		for (int i = 1; i <= p; i += 2)
		{
			total += getDigitFromIndex(number, i);
		}
		return total;

	}
	public static boolean prefixMatched(long number, int d)
	{
		if (getPrefix(number, getSize(d)) == d)
		{
			return true;
		}
		else
		{
			return false;
		}

	}
	public static int getSize(long d)
	{
		int nod = 1;
		while ((d = d / 10) != 0)
		{
			nod++;
		}
		return nod;

	}
	public static long getPrefix(long number, int k)
	{
		int num3 = getSize(number);
		return number / (long) (Math.pow(10.0, (double) (num3 - k)));

	}

}