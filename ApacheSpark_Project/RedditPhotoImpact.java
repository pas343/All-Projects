package com.RUSpark;

import java.util.List;

import org.apache.spark.api.java.JavaPairRDD;
import org.apache.spark.api.java.JavaRDD;
import org.apache.spark.sql.Row;
import org.apache.spark.sql.SparkSession;

import scala.Tuple2;

/* any necessary Java packages here */

public class RedditPhotoImpact {

	public static void main(String[] args) throws Exception {

    if (args.length < 1) {
      System.err.println("Usage: RedditPhotoImpact <file>");
      System.exit(1);
    }
		
		String InputPath = args[0];
		
		/* Implement Here */
        SparkSession spark = SparkSession
                .builder()
                .appName("RedditPhotoImpact")
                .getOrCreate();
        photoImpact(InputPath, spark);
        spark.close();
    }

    private static void photoImpact(String inputPath, SparkSession spark) 
    {

        JavaRDD<Row> reddit_photos = spark.read().csv(inputPath).javaRDD();

        JavaPairRDD<String, Long> photoImpactScores = reddit_photos.mapToPair(row -> 
        {
            String image_id = row.getString(0);
            Long upVote = Long.parseLong(row.getString(4));
            Long downVote = Long.parseLong(row.getString(5));
            Long comments = Long.parseLong(row.getString(6));
            Long postImpactScore = upVote + downVote + comments;
            return new Tuple2<>(image_id, postImpactScore);
        }).reduceByKey(Long::sum);

        List<Tuple2<String, Long>> output = photoImpactScores.collect();
        for (Tuple2<?, ?> tuple : output) 
        {
            System.out.println(tuple._1() + " " + tuple._2());
        }
    }
}