-- TODO: create tables
CREATE TABLE technologies (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  name TEXT UNIQUE NOT NULL,
  -- media
  definition TEXT UNIQUE NOT NULL,
  example TEXT,
  description TEXT UNIQUE NOT NULL,
  FOREIGN KEY(resource_id) REFERENCES resources(id),
  FOREIGN KEY(review_id) REFERENCES reviews(id),
  FOREIGN KEY(tag_id) REFERENCES tags(id)
);

CREATE TABLE resources (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  name TEXT NOT NULL,
  topic TEXT NOT NULL,
  url TEXT NOT NULL
);

CREATE TABLE reviews (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  mean_rating INTEGER NOT NULL,
  reviews_count INTEGER NOT NULL,
  hot_count INTEGER NOT NULL,
  hot_vote_count INTEGER NOT NULL
);

CREATE TABLE tags (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  name INT UNIQUE NOT NULL,
);

-- initial seed data
INSERT INTO
  technologies (
    name,
    definition,
    example,
    description,
    resource_id,
    review_id,
    tag_id
  )
VALUES
  (
    'OCaml',
    'A high-level programming language',
    'let x = 4 :: 2 :: 3 :: []',
    'OCaml is a powerful and expressive programming language for functional programming',
    1,
    1,
    3
  );

INSERT INTO
  technologies (
    name,
    definition,
    example,
    description,
    resource_id,
    review_id,
    tag_id
  )
VALUES
  (
    'JUnit',
    'A unit testing framework for Java',
    'Example test case goes here',
    'JUnit is a popular open-source unit testing framework for Java programming language.',
    2,
    2,
    3
  );

INSERT INTO
  resources (name, topic, url)
VALUES
  (
    'FreeCodeCamp',
    'OCaml',
    'https://www.freecodecamp.org/'
  );

INSERT INTO
  resources (name, topic, url)
VALUES
  (
    'TestResources',
    'JUnit',
    'https://www.example.com/junit'
  );

INSERT INTO
  reviews (
    mean_rating,
    reviews_count,
    hot_count,
    hot_vote_count
  )
VALUES
  (3, 10, 5, 20);

INSERT INTO
  reviews (
    mean_rating,
    reviews_count,
    hot_count,
    hot_vote_count
  )
VALUES
  (4, 10, 12, 22);

INSERT INTO
  tags (name)
VALUES
  (1);

INSERT INTO
  tags (name)
VALUES
  (5);
