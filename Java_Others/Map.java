import java.util.Scanner;

public class Map
{
	public static void main(String args[])
	{
		Scanner input = new Scanner(System.in);



		System.out.print("Please enter your phone number: ");
		String num = input.nextLine();

		for(int i = 0; i < num.length(); i++)
		{
			char ch = num.charAt(i);
			if(Character.isLetter(ch))
			{
				ch = Character.toUpperCase(ch);
				int num1 = getNumber(ch);
				System.out.print(num1);
			}
			else
			{
				System.out.print(ch);
			}



		}
		System.out.println();
	}
		public static int getNumber(char uppercaseLetter)
		{
			int num2 = 0;
			switch(uppercaseLetter)
			{
						case 'A':
				        case 'B':
				        case 'C':
				            num2 = '2';
				            break;
				        case 'D':
				        case 'E':
				        case 'F':
				            num2 = '3';
				            break;
				        case 'G':
				        case 'H':
				        case 'I':
				            num2 = '4';
				            break;
				        case 'J':
				        case 'K':
				        case 'L':
				            num2 = '5';
				            break;
				        case 'M':
				        case 'N':
				        case 'O':
				            num2 = '6';
				            break;
				        case 'P':
				        case 'Q':
				        case 'R':
				        case 'S':
				            num2 = '7';
				            break;
				        case 'T':
				        case 'U':
				        case 'V':
				            num2 = '8';
				            break;
				        case 'W':
				        case 'X':
				        case 'Y':
				        case 'Z':
				          	num2 = '9';
				            break;
				}
				return num2;
			}
		}
