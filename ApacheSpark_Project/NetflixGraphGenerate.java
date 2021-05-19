package com.RUSpark;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import org.apache.spark.api.java.JavaPairRDD;
import org.apache.spark.api.java.JavaRDD;
import org.apache.spark.sql.Row;
import org.apache.spark.sql.SparkSession;

import scala.Tuple2;

/* any necessary Java packages here */

public class NetflixGraphGenerate {

	public static void main(String[] args) throws Exception 
	{

    if (args.length < 1) {
      System.err.println("Usage: NetflixGraphGenerate <file>");
      System.exit(1);
    }
		
		String InputPath = args[0];
		
		/* Implement Here */ 
		 SparkSession spark = SparkSession
	                .builder()
	                .appName("NetflixGraphGenerate")
	                .getOrCreate();
	        graphGenerate(InputPath, spark);
	        spark.close();
	    }

	    private static void graphGenerate(String inputPath, SparkSession spark) {

	        JavaRDD<Row> movie_ratings = spark.read().csv(inputPath).javaRDD();

	        JavaPairRDD<Tuple2<String, String>, Long> graph = movie_ratings.mapToPair(row -> 
	        {
	            String movie_id = row.getString(0);
	            String avg_rating = row.getString(2);
	            String customer_id = row.getString(1);
	            return new Tuple2<>(new Tuple2<>(movie_id, avg_rating), customer_id);
	        }).groupByKey().flatMap(group ->
	        {
	            List<String> customer_ids = new ArrayList<>();
	            group._2().forEach(customer_ids::add);
	            Collections.sort(customer_ids);
	            List<Tuple2<String, String>> edge = new ArrayList<>();
	            for (int i = 0; i < customer_ids.size() - 1; i++) 
	            {
	                for (int j = i + 1; j < customer_ids.size(); j++) 
	                {
	                	edge.add(new Tuple2<>(customer_ids.get(i), customer_ids.get(j)));
	                }
	            }
	            return edge.iterator();
	        }).mapToPair(edges -> new Tuple2<>(edges, 1L)).reduceByKey(Long::sum);

	        List<Tuple2<Tuple2<String, String>, Long>> output = graph.collect();

	        for (Tuple2<?, ?> tuple : output) 
	        {
	            System.out.println(tuple._1() + " " + tuple._2());
	        }
	    }
	}
