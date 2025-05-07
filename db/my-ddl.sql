# Drops all tables. This section should be amended if new tables are added.
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS tool;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS interface;
DROP TABLE IF EXISTS developer;
DROP TABLE IF EXISTS publication;
DROP TABLE IF EXISTS tool_category;
DROP TABLE IF EXISTS tool_publication;
DROP TABLE IF EXISTS tool_interface;
DROP TABLE IF EXISTS tool_developer;
SET FOREIGN_KEY_CHECKS=1;

# Section 1, create Tool table
CREATE TABLE tool (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    program_lang VARCHAR(50) NOT NULL,
    last_updated DATE NOT NULL,
    repo VARCHAR(255) NOT NULL,
    doc VARCHAR(255) NOT NULL
);

# Insert data in table
INSERT INTO tool (name, program_lang, last_updated, repo, doc) VALUES
('BWA', 'C', '2024-04-14', 'https://github.com/lh3/bwa', 'https://bio-bwa.sourceforge.net/bwa.shtml'),
('Minimap2', 'C', '2024-03-27', 'https://github.com/lh3/minimap2', 'https://lh3.github.io/minimap2/minimap2.html'),
('shinyGEO', 'R', '2021-04-13', 'https://github.com/gdancik/shinyGEO', 'http://gdancik.github.io/shinyGEO/'),
('RAxML-NG', 'C++', '2024-07-31', 'https://github.com/amkozlov/raxml-ng', 'https://github.com/amkozlov/raxml-ng/wiki'),
('Clustal Omega', 'C', '2018-01-02', 'https://github.com/GSLBiotech/clustal-omega', 'http://www.clustal.org/omega/#Documentation'),
('adephylo', 'R', '2023-10-06', 'https://github.com/adeverse/adephylo', 'https://cran.r-project.org/web/packages/adephylo/adephylo.pdf'),
('Java TreeView', 'Java', '2024-09-30', 'https://sourceforge.net/p/jtreeview/git/ci/master/tree/', 'https://jtreeview.sourceforge.net/manual.html');

# Secton 2
CREATE TABLE category (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

-- Sample data insertion
INSERT INTO category (name) VALUES
('Read alignment'),
('Multiple sequence alignment'),
('Differential gene expression analysis'),
('Phylogenetic tree inference'),
('Phylogenetic analysis'),
('Microarray data visualization');

# Section 3
CREATE TABLE interface (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(50) NOT NULL
);

-- Sample data insertion
INSERT INTO interface (type) VALUES
('Web-based'),
('CLI'),
('GUI'),
('R package'),
('Python module');

# Section 4
CREATE TABLE developer (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

-- Sample data insertion
INSERT INTO developer (name) VALUES
('Heng Li'),
('Richard Durbin'),
('Jasmine Dumas'),
('Michael Gargano'),
('Garrett Dancik'),
('Alexey Kozlov'),
('Alexandros Stamatakis'),
('Diego Darriba'),
('Tomas Flouri'),
('Des Higgins'),
('Fabian Sievers'),
('David Dineen'),
('Andreas Wilm'),
('Stephane Dray'),
('Thibaut Jombart'),
('Anders Ellern Bilgrau'),
('Aurelie Siberchicot'),
('Alok Saldanha');

# Section 5
CREATE TABLE publication (
    DOI VARCHAR(50) PRIMARY KEY,
    tool_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    first_author VARCHAR(100) NOT NULL,
    year DATE NOT NULL,
    journal VARCHAR(100) NOT NULL,
    citations INT NOT NULL,
    FOREIGN KEY (tool_id) REFERENCES tool(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Sample data insertion
INSERT INTO publication (DOI, tool_id, title, first_author, year, journal, citations) VALUES
('10.1093/bioinformatics/btp324', 1, 'Fast and accurate short read alignment with Burrows-Wheeler transform', 'Heng Li', '2009-07-15', 'Bioinformatics', 40391),
('10.1093/bioinformatics/btp698', 1, 'Fast and accurate long-read alignment with Burrows-Wheeler transform', 'Heng Li', '2010-03-01', 'Bioinformatics', 10245),
('10.48550/arXiv.1303.3997', 1, 'Aligning sequence reads, clone sequences and assembly contigs with BWA-MEM', 'Heng Li', '2013-05-26', 'q-bio.GN', 10797),
('10.1093/bioinformatics/bty191', 2, 'Minimap2: pairwise alignment for nucleotide sequences', 'Heng Li', '2018-09-15', 'Bioinformatics', 9265),
('10.1093/bioinformatics/btab705', 2, 'New strategies to improve minimap2 alignment accuracy', 'Heng Li', '2021-12-07', 'Bioinformatics', 522),
('10.1093/bioinformatics/btw519', 3, 'shinyGEO: a web-based application for analyzing gene expression omnibus datasets', 'Jasmine Dumas', '2016-12-01', 'Bioinformatics', 67),
('10.1093/bioinformatics/btz305', 4, 'RAxML-NG: a fast, scalable and user-friendly tool for maximum likelihood phylogenetic inference', 'Alexey Kozlov', '2019-11-01', 'Bioinformatics', 2758),
('10.1038/msb.2011.75', 5, 'Fast, scalable generation of high-quality protein multiple sequence alignments using Clustal Omega', 'Fabian Sievers', '2011-10-11', 'Molecular Systems Biology', 12976),
('10.1002/pro.3290', 5, 'Clustal Omega for making accurate alignments of many protein sequences', 'Fabian Sievers', '2017-09-07', 'Protein Science', 1413),
('10.1093/bioinformatics/btq292', 6, 'adephylo: new tools for investigating the phylogenetic signal in biological traits', 'Thibaut Jombart', '2010-08-01', 'Bioinformatics', 359),
('10.1093/bioinformatics/bth349', 7, 'Java Treeview - extensible visualization of microarray data', 'Alok Saldanha', '2004-11-01', 'Bioinformatics', 2707);

# JUNCTION TABLES
#Section 6

CREATE TABLE tool_category (
    id INT NOT NULL AUTO_INCREMENT,
    tool_id INT NOT NULL,
    cat_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (tool_id) REFERENCES tool(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (cat_id) REFERENCES category(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Sample data insertion
INSERT INTO tool_category (tool_id, cat_id) VALUES
(1, 1),
(2, 1),
(3, 3),
(4, 4),
(5, 2),
(6, 5),
(7, 6);

# Section 7
CREATE TABLE tool_interface (
    id INT NOT NULL AUTO_INCREMENT,
    tool_id INT NOT NULL,
    interface_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (tool_id) REFERENCES tool(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (interface_id) REFERENCES interface(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Sample data insertion
INSERT INTO tool_interface (tool_id, interface_id) VALUES
(1, 2),
(2, 2),
(3, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 4),
(7, 3);

# Section 8
CREATE TABLE tool_developer (
    id INT NOT NULL AUTO_INCREMENT,
    tool_id INT NOT NULL,
    dev_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (tool_id) REFERENCES tool(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (dev_id) REFERENCES developer(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Sample data insertion
INSERT INTO tool_developer (tool_id, dev_id) VALUES
(1, 1),
(1, 2),
(2, 1),
(3, 3),
(3, 4),
(3, 5),
(4, 6),
(4, 7),
(4, 8),
(4, 9),
(5, 10),
(5, 11),
(5, 12),
(5, 13),
(6, 14),
(6, 15),
(6, 16),
(6, 17),
(7, 18);
