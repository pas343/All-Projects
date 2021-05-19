import java.util.Scanner;

public class Grades
{
	public static void main(String[] args)
	{
		Scanner input = new Scanner(System.in);
    	System.out.print("Enter number of students: ");
    	int numStud = input.nextInt();
    	double[] scores = new double[numStud];
    	double bestScore = 0;
    	char grade;

    	System.out.print("Enter " + numStud + " scores: ");

    	for (int i = 0; i < scores.length; i++)
    	{
			scores[i] = input.nextDouble();
			if (scores[i] > bestScore)
			{
				bestScore = scores[i];
			}
		}

		System.out.println();

    	for (int i = 0; i < scores.length; i++)
    	{
      		if (scores[i] >= bestScore - 10)
        	{
				grade = 'A';
			}
      		else if (scores[i] >= bestScore - 20)
        	{
				grade = 'B';
			}
      		else if (scores[i] >= bestScore - 30)
        	{
				grade = 'C';
			}
      		else if (scores[i] >= bestScore - 40)
        	{
				grade = 'D';
			}
      		else
        	{
				grade = 'F';
			}
      		System.out.println("Student " + i + " score is " +
        						scores[i] + " and grade is " + grade);
    }
  }
}