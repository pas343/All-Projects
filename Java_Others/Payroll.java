import java.util.Scanner;

public class Payroll
{
	public static void main(String args[])
	{
		Scanner input = new Scanner(System.in);

		System.out.print("Enter employee's name: ");
		String empName = input.next();

		System.out.print("Enter number of hours worked in a week: ");
		double empHrs = input.nextDouble();

		System.out.print("Enter hourly pay rate: ");
		double empRate = input.nextDouble();

		System.out.print("Enter federal tax withholding rate: ");
		double fedTax = input.nextDouble();

		System.out.print("Enter state tax withholding rate: ");
		double stateTax = input.nextDouble();

		double grossPay = empHrs * empRate;

		double fedDeduction = fedTax * grossPay;

		double stateDeduction = stateTax * grossPay;

		double totalDeduction = fedDeduction + stateDeduction;

		double netPay = grossPay - totalDeduction;

		System.out.println("\nPayroll Statment");
		System.out.println("Employee Name: " + empName);
		System.out.println("Hours worked: " + empHrs);
		System.out.printf("Pay Rate: %.2f\n", empRate);
		System.out.printf("Gross Pay: %.2f\n", grossPay);
		System.out.println("Deductions:");
		System.out.printf("  Federal Withholding: %.2f\n", fedDeduction);
		System.out.printf("  State Withholding: %.2f\n", stateDeduction);
		System.out.printf("  Total Deduction: %.2f\n", totalDeduction);
		System.out.printf("Net Pay: %.2f\n", netPay);
	}
}