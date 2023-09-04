--- Reviews ---
CREATE TABLE reviews (
  id INTEGER NOT NULL UNIQUE,
  rating_mean REAL NOT NULL,
  rating_count INTEGER NOT NULL,
  hot_yes_count INTEGER NOT NULL,
  hot_pct REAL NOT NULL,
  hot_count INTEGER NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

--- Techs/Technologies ---
CREATE TABLE techs (
  id INTEGER NOT NULL UNIQUE,
  name TEXT NOT NULL UNIQUE,
  definition TEXT NOT NULL UNIQUE,
  example TEXT,
  description TEXT NOT NULL UNIQUE,
  file_ext TEXT NOT NULL,
  file_source TEXT,
  resource_name TEXT NOT NULL,
  resource_url TEXT NOT NULL,
  review_id INTEGER NOT NULL UNIQUE,
  PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY(review_id) REFERENCES reviews(id)
);

--- Tags ---
CREATE TABLE tags (
  id INTEGER NOT NULL UNIQUE,
  name TEXT UNIQUE NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

-- Tech and Tags entries --
CREATE TABLE tech_tags (
  id INTEGER NOT NULL UNIQUE,
  tech_id INTEGER NOT NULL,
  tag_id INTEGER NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY (tech_id) REFERENCES techs(id) FOREIGN KEY (tag_id) REFERENCES tags(id)
);

--- Users ---
CREATE TABLE users (
  id INTEGER NOT NULL UNIQUE,
  name TEXT NOT NULL,
  email TEXT,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

--- Sessions ---
CREATE TABLE sessions (
  id INTEGER NOT NULL UNIQUE,
  user_id INTEGER NOT NULL,
  session TEXT NOT NULL UNIQUE,
  last_login TEXT NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY(user_id) REFERENCES users(id)
);

-- Reviews Seed Data --
INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.8, 10, 16, 0.8, 20);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.0, 10, 20, 0.91, 22);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.8, 22, 23, 0.96, 24);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.6, 18, 21, 0.88, 24);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.9, 26, 31, 0.97, 32);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.7, 24, 28, 0.93, 30);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.4, 16, 16, 0.84, 19);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.1, 17, 17, 0.85, 20);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.2, 15, 42, 0.86, 49);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.8, 34, 37, 0.93, 40);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.9, 28, 41, 0.98, 42);

INSERT INTO
  reviews (
    rating_mean,
    rating_count,
    hot_yes_count,
    hot_pct,
    hot_count
  )
VALUES
  (4.4, 32, 27, 0.9, 30);

-- Tags Seed Data --
INSERT INTO
  tags (name)
VALUES
  ('Programming Language');

INSERT INTO
  tags (name)
VALUES
  ('Web Development');

INSERT INTO
  tags (name)
VALUES
  ('App Development');

INSERT INTO
  tags (name)
VALUES
  ('Data Science, ML and AI');

INSERT INTO
  tags (name)
VALUES
  ('Game Development');

INSERT INTO
  tags (name)
VALUES
  ('Operating Systems');

INSERT INTO
  tags (name)
VALUES
  ('Software Testing');

INSERT INTO
  tags (name)
VALUES
  ('Development Tools');

-- Techs Seed Data --
INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'OCaml',
    'A high-level programming language that emphasizes strong static typing, type inference, and pattern matching',
    'let x = 4 :: 2 :: 3 :: [] in
    match x with
    | [] -> [891]
    | h :: t -> t
    | _ -> faiwith "Not a List!!"',
    'OCaml is a functional programming language that is used for a variety of applications, including scientific computing, symbolic computation, and systems programming. It is designed to be expressive, concise, and safe, with a strong type system that helps catch errors at compile time. OCaml also features type inference, garbage collection, and support for imperative programming, making it a versatile language that can handle a wide range of programming paradigms. One of the key features of OCaml is its use of pattern matching, which allows developers to write concise and expressive code for data manipulation and processing. OCaml is often used in the development of compilers, interpreters, and other tools for programming languages, due to its strong typing, efficient memory management, and support for abstract data types. Additionally, OCaml is commonly used in the development of web applications, database systems, and financial software. Its popularity has also been driven by the fact that it is an open-source language with an active community of developers who contribute libraries, tools, and frameworks. Overall, OCaml is a powerful and flexible programming language that can be used in a variety of contexts and applications.',
    "png",
    "https://icon-icons.com/icon/file-type-ocaml/130288",
    'FreeCodeCamp',
    'https://www.freecodecamp.org/',
    1
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'JUnit',
    'JUnit is a unit testing framework for Java that provides a simple way to write and run repeatable automated tests to ensure the code behaves as expected.',
    '@Test
      public void testAdd() {
        Calculator calculator = new Calculator();
        int result = calculator.add(2, 3);
        assertEquals(5, result);
    }',
    'JUnit is an open-source testing framework for the Java programming language, designed to help developers write and run repeatable tests. It provides a set of annotations and assertion methods that allow developers to test their code in a structured and organized manner. Developers can use JUnit to create a suite of tests to ensure their code works as expected, and to identify and fix issues quickly. JUnit allows developers to automate their testing process, which is particularly useful when dealing with large codebases or when making changes to existing code. JUnit tests are written in Java and can be integrated with most popular development environments, such as Eclipse or IntelliJ IDEA. Developers can write tests for individual units of code (unit testing), or for entire applications (integration testing). Test results are displayed in a user-friendly format, indicating which tests passed and which failed. JUnit is widely used in the industry and is considered a standard for unit testing in Java. Its popularity is due to its simplicity, ease of use, and integration with popular development environments. JUnit has become a crucial tool for ensuring software quality and minimizing the risk of bugs and errors in Java applications.',
    "png",
    "https://www.opsera.io/platform/unified-insights",
    'TestResources',
    'https://www.tutorialspoint.com/junit/junit_test_framework.htm',
    2
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'Python',
    'Python is a high-level, interpreted programming language known for its simplicity, readability, and ease of use. It is general purpose and used across the software engineering industry',
    'def greet(name):
      print(f"Hello, {name}!")

    greet("John")',
    "Python is a versatile programming language that can be used for a variety of purposes, from web development to data analysis to artificial intelligence. Its syntax is easy to read and write, making it a popular choice for beginners and experts alike. Python has a large standard library and a vast collection of third-party packages that can be easily installed using a package manager such as pip. Python also supports multiple programming paradigms, including object-oriented, functional, and procedural programming. Python's simplicity and ease of use make it a popular choice for rapid prototyping and development. Its extensive library support and community make it a go-to choice for data science and machine learning applications. Python code can be executed using an interpreter or compiled into byte code and run on a virtual machine. Python is open-source and has a large and active community that contributes to its development and maintenance.",
    'png',
    'https://commons.wikimedia.org/wiki/File:Python-logo-notext.svg',
    'Python Documentation',
    'https://docs.python.org/3/',
    3
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'Linux',
    'Linux is a free and open-source operating system that is based on the Unix operating system.',
    '$ ls -l
      total 4
      drwxr-xr-x  2 user user 4096 Apr 30 2023 example_directory',
    'Linux is a family of free and open-source software operating systems that are built around the Linux kernel. Linux is known for its stability, security, and flexibility, and is widely used for servers, supercomputers, mobile devices, and embedded systems. It is also popular among developers, as it provides a powerful command-line interface and a vast range of tools and utilities. Linux comes in many different distributions, or "distros", each with its own set of features, package management system, and user interface. Some of the most popular Linux distros include Ubuntu, Fedora, and Debian. Linux is known for its customizability and modularity, allowing users to configure the operating system to suit their needs. It also has a vibrant open-source community, which contributes to the development of new features and bug fixes. Linux supports a wide range of programming languages and development environments, making it an ideal platform for software development. Linux is distributed under various open-source licenses, which allows anyone to use, modify, and distribute the operating system and its source code freely.',
    "png",
    "https://www.freepnglogos.com/pics/linux",
    'Ubuntu',
    'https://ubuntu.com/',
    4
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'Java',
    'Java is a high-level, class-based, object-oriented programming language that is designed to have as few implementation dependencies as possible. Java code can be executed across platforms that support it without re-complitation. It adheres to strong OOP principles.',
    'public class HelloWorld {
      public static void main(String[] args) {
          System.out.println("Hello, World!");
      }
    }',
    "Java is one of the most popular programming languages in use today, particularly for enterprise and web applications. It was originally developed by Sun Microsystems and later acquired by Oracle Corporation. Java is designed to be portable, secure, and platform-independent, meaning that it can run on any operating system or platform that has a Java Virtual Machine (JVM) installed. Java's syntax is similar to that of C++, but it is easier to read and write than C++. Java is also an object-oriented language, which means that it is designed around the concept of objects, which can contain data and methods for manipulating that data. Java is used for a wide range of applications, from desktop and mobile apps to web servers and enterprise software. It is also the primary language used for developing Android apps.",
    "png",
    "https://www.dreamstime.com/java-logo-editorial-illustrative-white-background-eps-download-vector-jpeg-banner-java-logo-editorial-illustrative-white-image208329454",
    'Java Documentation',
    'https://docs.oracle.com/en/java/javase/16/docs/api/index.html',
    5
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'React',
    'React is a JavaScript library for building user interfaces. It allows developers to create reusable UI components and manage the state of their applications in a declarative way.',
    'function App() {
      return (
        <div className="App">
          <h1>Hello, World!</h1>
        </div>
      );
    }',
    "React is a popular JavaScript library used for building user interfaces. It was developed by Facebook and is now maintained by Facebook and a community of individual developers and companies. React allows developers to build complex UIs by breaking them down into smaller, reusable components. Each component can have its own state, which can be managed in a declarative way. React uses a Virtual DOM, which allows it to efficiently update the UI when the underlying data changes. React components can be used with other JavaScript libraries or frameworks, such as Redux or Angular. React is often used for building single-page applications (SPAs) and mobile applications using React Native. Its popularity is due to its performance, flexibility, and large community of developers and resources available online.",
    "png",
    "https://en.wikipedia.org/wiki/React_%28software%29",
    'React Docs',
    'https://reactjs.org/docs/getting-started.html',
    6
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'Unity',
    'Unity is a cross-platform game engine developed by Unity Technologies, used to develop video games and other interactive content for multiple platforms, including mobile devices, desktops, and consoles.',
    'using UnityEngine;
    using System.Collections;

    public class ExampleScript : MonoBehaviour {
        void Start() {
            Debug.Log("Hello, Unity!");
        }
    }',
    "Unity is a popular game engine used to develop games and interactive content across multiple platforms. It was initially released in 2005 and has since become one of the most widely used game engines in the industry. Unity supports a variety of programming languages, including C#, C++, and JavaScript. It provides a powerful editor that allows developers to create scenes, assets, and animations for their games. Unity also has a large community of developers who contribute to its ecosystem, providing resources, plugins, and tutorials for developers to learn from. Unity supports a variety of platforms, including Windows, macOS, Linux, Android, iOS, and many game consoles. It provides powerful tools for creating 2D and 3D games, and its built-in physics engine makes it easy to create realistic game worlds. Unity also provides a powerful scripting system that allows developers to customize the engine to their needs. Unity's popularity is due in part to its ease of use, powerful features, and large community of developers.",
    'png',
    "https://mahara.dkit.ie/artefact/artefact.php?artefact=300313&view=82733",
    'Unity Documentation',
    'https://docs.unity3d.com/Manual/index.html',
    7
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'R',
    'R is a functional programming language and environment for statistical computing, data science and visualizations. It provides a wide variety of statistical and graphical techniques, to analyze and work with data',
    'library(ggplot2)
      data(mtcars)
      ggplot(mtcars, aes(x = wt, y = mpg)) +
      geom_point() +
      ggtitle("Miles per Gallon vs. Weight")',
    'R is an open-source programming language and environment for statistical computing and graphics. It provides a wide variety of statistical and graphical techniques, including linear and nonlinear modeling, time-series analysis, clustering, and more. R is widely used by data analysts, statisticians, and researchers for data analysis and visualization. R can be used for a wide range of applications, including data cleaning and manipulation, data visualization, statistical analysis, machine learning, and more. R has a large and active community, which contributes to a vast collection of packages and libraries that extend its functionality. R can be run from the command line or from a graphical user interface. RStudio is a popular integrated development environment for R, which provides a user-friendly interface for coding, debugging, and data analysis. R is a powerful tool for data analysis and visualization, and its popularity is due to its versatility, flexibility, and extensibility.',
    "png",
    "https://www.r-project.org/logo/",
    'R for Data Science',
    'https://r4ds.had.co.nz/',
    8
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'Docker',
    'Docker is a platform for building, shipping, and running distributed applications in containers. It provides an easy way to create, deploy, and run applications by packaging them in a container with all of their dependencies.',
    'FROM node:14-alpine
    WORKDIR /app
    COPY package*.json ./
    RUN npm install
    COPY . .
    EXPOSE 3000
    CMD [ "npm", "start" ]
',
    'Docker is a containerization platform that allows developers to package an application with all of its dependencies into a single container. Containers are lightweight, portable, and provide a consistent environment for the application to run in, regardless of the host system. Docker provides a simple and efficient way to build, deploy, and manage applications by packaging them into containers, which can be easily moved between environments. With Docker, developers can create a container for each component of their application, and manage them all as a single unit. This makes it easy to deploy applications to any environment, whether it be a local development environment, a staging environment, or a production environment. Docker also provides a way to manage infrastructure as code, by using Dockerfiles to define the configuration of the container images. This allows developers to version control their infrastructure and ensure that deployments are consistent and reproducible.',
    "png",
    "https://www.docker.com/company/newsroom/media-resources/",
    'Docker Documentation',
    'https://docs.docker.com/',
    9
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'VSCode',
    'Visual Studio Code (VSCode) is a free, open-source source code editor developed by Microsoft. It supports a wide range of programming languages and provides a customizable, extensible development environment with features like debugging, syntax highlighting, code completion, and version control integration.',
    '// Example JavaScript code in VSCode
    function greet(name) {
      console.log(`Hello, ${name}!`);
    }

    greet("World");',
    'VSCode is a popular code editor used by developers across various programming languages. It offers a wide range of features and extensions that make it easier to write, debug, and maintain code. Some of its notable features include IntelliSense, which provides intelligent code completion and hints based on the context, a built-in debugger, and support for Git version control. VSCode also provides support for various file types and programming languages, and developers can install extensions to add new functionality or support for new languages. VSCode has gained popularity in recent years due to its cross-platform compatibility and extensive library of extensions, making it a powerful tool for developers.',
    "png",
    "https://commons.wikimedia.org/wiki/File:Visual_Studio_Code_1.35_icon.svg",
    'VSCode Official Site',
    'https://code.visualstudio.com/docs',
    10
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'JavaScript',
    'JavaScript is a popular programming language that is used to create dynamic and interactive web pages. It is unique as it carries strong properties of both Object Oriented and Functional Programming',
    'function greet(name) {
      console.log("Hello, " + name + "!");
    }

    greet("John"); // Output: Hello, John!',
    'JavaScript is a high-level, dynamic, and interpreted programming language that is used to create interactive web pages. It is often used in conjunction with HTML and CSS to add functionality and interactivity to websites. JavaScript code can be embedded directly into HTML pages or included as separate files. JavaScript is used to create web applications, browser extensions, and other client-side applications. It is also used on the server-side with Node.js to create full-stack applications. JavaScript is a versatile language that has a wide range of use cases and is supported by all modern web browsers. JavaScript code is executed by the browser at runtime and can manipulate web page elements, create animations, and interact with web APIs. JavaScript is constantly evolving, and new frameworks and libraries are being developed all the time to make it easier to write complex applications. Some popular frameworks and libraries include React, Angular, and Vue.js.',
    "jpeg",
    "https://commons.wikimedia.org/wiki/File:JavaScript-logo.png",
    'MDN Web Docs',
    'https://developer.mozilla.org/en-US/docs/Web/JavaScript',
    11
  );

INSERT INTO
  techs (
    name,
    definition,
    example,
    description,
    file_ext,
    file_source,
    resource_name,
    resource_url,
    review_id
  )
VALUES
  (
    'C++',
    'C++ is a high-level, general-purpose programming language that is widely used for developing applications, system software, device drivers, embedded software, and video games.',
    '#include <iostream>
    using namespace std;
    int main()
    {
        cout << "Hello, world!" << endl;
        return 0;
    }',
    'C++ is an extension of the C programming language that supports object-oriented programming (OOP). It was created in the early 1980s by Bjarne Stroustrup as an enhancement to the C language. C++ provides a wide range of features, such as classes, templates, exceptions, and operator overloading, that make it a powerful and flexible language. It is commonly used for developing software that requires high performance, such as video games and operating systems. C++ code can be compiled into machine code, which makes it suitable for developing low-level applications. C++ also provides a large set of standard libraries, such as the Standard Template Library (STL), which makes it easier to write complex programs. C++ is a complex language and can be difficult to learn, but its power and flexibility make it a popular choice among developers.',
    "png",
    "https://www.freeiconspng.com/images/c-logo-icon",
    'C++ Programming Language',
    'https://www.cplusplus.com/',
    12
  );

-- insert into tech_tags
INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (1, 1);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (1, 4);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (2, 7);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (2, 8);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (3, 1);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (3, 2);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (3, 4);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (4, 6);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (4, 8);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (5, 1);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (5, 2);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (5, 3);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (5, 5);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (6, 2);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (6, 3);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (7, 1);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (7, 5);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (8, 1);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (8, 4);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (9, 2);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (9, 8);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (10, 7);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (10, 8);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (11, 1);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (11, 2);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (12, 1);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (12, 5);

INSERT INTO
  tech_tags (tech_id, tag_id)
VALUES
  (12, 6);

-- password: monkey
INSERT INTO
  users (id, name, email, username, password)
VALUES
  (
    1,
    'Asad Nabi',
    'an448@cornell.edu',
    'asad',
    '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.' -- monkey
  );

INSERT INTO
  users (id, name, email, username, password)
VALUES
  (
    2,
    'Asad Nabi',
    'an448@cornell.edu',
    'test123',
    '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.' -- monkey
  );
