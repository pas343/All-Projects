import java.util.Scanner;

public class MonthDay
{
	public static void main(String args[])
	{
		Scanner input = new Scanner(System.in);

		System.out.print("Please enter a year: ");
		int year = input.nextInt();

		System.out.print("Please enter a month 1-12 : ");
		int month = input.nextInt();

		if(month == 1)
		{
			month = 13;
			year--;
		}
		if(month == 2)
		{
			month = 14;
			year--;
		}

		System.out.print("Please enter day of the month 1-31: ");
		int day = input.nextInt();

		int dayOfTheWeek = (day + ((26 * (month + 1))/ 10) + (year % 100) + ((year % 100) / 4) + ((year/100)/4) + (5 *(year/100))) % 7;

		switch(month)
		{
			case 13: System.out.print("The name of the month is January");
					 break;
			case 14: System.out.print("The name of the month is February");
					 break;
			case 3: System.out.print("The name of the month is March");
					 break;
			case 4: System.out.print("The name of the month is April");
					 break;
			case 5: System.out.print("The name of the month is May");
					 break;
			case 6: System.out.print("The name of the month is June");
					 break;
			case 7: System.out.print("The name of the month is July");
					 break;
			case 8: System.out.print("The name of the month is August");
					 break;
			case 9: System.out.print("The name of the month is September");
					 break;
			case 10: System.out.print("The name of the month is October");
					 break;
			case 11: System.out.print("The name of the month is November");
					 break;
			case 12: System.out.print("The name of the month is December");
					 break;
		}

		switch(dayOfTheWeek)
		{
			case 0: System.out.println(" and day of the week is Saturday.");
					break;
			case 1: System.out.println(" and day of the week is Sunday.");
					break;
			case 2: System.out.println(" and day of the week is Monday.");
					break;
			case 3: System.out.println(" and day of the week is Tuesday.");
					break;
			case 4: System.out.println(" and day of the week is Wednesday.");
					break;
			case 5: System.out.println(" and day of the week is Thursday.");
					break;
			case 6: System.out.println(" and day of the week is Friday.");
					break;
		}
	}
}


