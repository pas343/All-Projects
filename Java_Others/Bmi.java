import java.util.Scanner;

public class Bmi
{
	public static void main(String args[])
	{
		Scanner input = new Scanner(System.in);

		System.out.print("Please enter your weight in pounds and height in inches to count your BMI: ");
		double pounds = input.nextDouble();
		double inch = input.nextDouble();

		double weight = pounds * 0.45359237;
		double height = inch * 0.0254;

		double bmi = (weight / ( height * height));

		System.out.println("Your bmi is " + bmi);
	}
}




