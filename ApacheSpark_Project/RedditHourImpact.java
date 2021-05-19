package com.RUSpark;

import java.time.Instant;
import java.time.ZoneId;
import java.time.ZonedDateTime;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.apache.spark.api.java.JavaPairRDD;
import org.apache.spark.api.java.JavaRDD;
import org.apache.spark.sql.Row;
import org.apache.spark.sql.SparkSession;

import scala.Tuple2;

/* any necessary Java packages here */

public class RedditHourImpact {

	public static void main(String[] args) throws Exception {

    if (args.length < 1) {
      System.err.println("Usage: RedditHourImpact <file>");
      System.exit(1);
    }
		
		String InputPath = args[0];
		
		/* Implement Here */ 
		SparkSession spark = SparkSession
                .builder()
                .appName("RedditHourImpact")
                .getOrCreate();
        hourImpact(InputPath, spark);
        spark.close();
    }

    private static void hourImpact(String inputPath, SparkSession spark) {

        JavaRDD<Row> reddit_photos = spark.read().csv(inputPath).javaRDD();

        JavaPairRDD<Integer, Long> impactScores = reddit_photos.mapToPair(row -> 
        {
            Instant i = Instant.ofEpochSecond(Long.parseLong(row.getString(1)));
            ZoneId z = ZoneId.of("America/New_York");
            ZonedDateTime date_time = ZonedDateTime.ofInstant(i, z);
            Integer h = date_time.getHour();
            Long upVotes = Long.parseLong(row.getString(4));
            Long downVotes = Long.parseLong(row.getString(5));
            Long comments = Long.parseLong(row.getString(6));
            Long postImpactScore = upVotes + downVotes + comments;
            return new Tuple2<>(h, postImpactScore);
        }).reduceByKey(Long::sum);

        List<Tuple2<Integer, Long>> output = impactScores.collect();

        Map<Integer, Long> hour_impact = new HashMap<>();
        for (Tuple2<Integer, Long> tuple : output) 
        {
            hour_impact.put(tuple._1(), tuple._2());
        }

        for (int i = 0; i < 24; i++) 
        {
            if (!hour_impact.containsKey(i)) 
            {
                hour_impact.put(i, 0L);
            }
            System.out.println(i + " " + hour_impact.get(i)) ;
        }

    }


}


