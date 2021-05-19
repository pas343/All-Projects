package com.RUSpark;

/* any necessary Java packages here */

import org.apache.spark.api.java.JavaRDD;
import org.apache.spark.sql.Row;
import org.apache.spark.sql.SparkSession;
import scala.Tuple2;

import java.math.RoundingMode;
import java.text.DecimalFormat;
import java.util.List;

public class NetflixMovieAverage {

    public static void main(String[] args) throws Exception {

        if (args.length < 1) {
            System.err.println("Usage: NetflixMovieAverage <file>");
            System.exit(1);
        }

        String inputPath = args[0];

        /* Implement Here */
        SparkSession spark = SparkSession
                .builder()
                .appName("NetflixMovieAverage")
                .getOrCreate();
        netflixMovieAverage(inputPath, spark);
        spark.close();
    }

    private static void netflixMovieAverage(String inputPath, SparkSession spark) {

        JavaRDD<Row> ratings = spark.read().csv(inputPath).javaRDD();

        JavaRDD<Tuple2<String, Float>> ratingCounts = ratings.mapToPair(row -> {
            String movieId = row.getString(0);
            Float rating = Float.parseFloat(row.getString(2));
            return new Tuple2<>(movieId, new Tuple2<>(rating, 1));
        }).reduceByKey((x, y) -> new Tuple2<>(x._1() + y._1(), x._2() + y._2()))
                .map(x -> new Tuple2<>(x._1(), x._2()._1() / x._2()._2()));

        List<Tuple2<String, Float>> output = ratingCounts.collect();

        DecimalFormat df = new DecimalFormat("##.##");
        df.setRoundingMode(RoundingMode.DOWN);

        for (Tuple2<String, Float> tuple : output) {
            System.out.println(tuple._1() + " " + df.format(tuple._2()));
        }
    }

}
