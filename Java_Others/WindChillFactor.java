import java.util.Scanner;


public class WindChillFactor
{
	public static void main(String args[])
	{
		Scanner input = new Scanner(System.in);

		System.out.print("Please enter a temperature between -58°F and 41°F and a wind speed greater than or equal to 2: ");
		double ta = input.nextDouble();
		double v = input.nextDouble();

		double twc = 35.74 + 0.6215 * ta - 35.75 * Math.pow(v,0.16) + 0.4275 * ta * Math.pow(v,0.16);

		System.out.println("Windchill tempreture is " + twc);
	}
}