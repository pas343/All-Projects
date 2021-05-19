import java.util.Scanner;

public class CurrencyConversion
{
	public static void main(String args[])
	{
		Scanner input = new Scanner(System.in);

		System.out.print("Enter the exchange rate from dollars to RMB: ");
		double rate = input.nextDouble();

		System.out.print("Please enter 0 to convert from U.S. dollars to Chinese RMB or enter 1 to convert from Chinese RMB" +
                         " to U.S. dollars: ");

		int choice = input.nextInt();

		if(choice == 0)
		{
			System.out.print("Please enter the ammount in U.S. dollars to convert it to Chinese RMB: ");
			double usc = input.nextDouble();

			double money = rate * usc;
			System.out.println("" + usc + "$ in Chinese RMB is " + money + " yarn.");
		}
		else if(choice == 1)
		{
			System.out.print("Please enter the ammount in Chinese RMB to convert it to U.S. dollars: ");
			double rmb = input.nextDouble();

			double rmbMoney = (rmb  * 1) / rate;

			System.out.println("" + rmb + " yarn in U.S. dollars is " + rmbMoney + "$.");
		}

		else
		{
			System.out.println("Invaid input, please enter either 0 or 1.");
		}



	}
}