package kg.apc.perfmon.metrics;

import junit.framework.Test;
import junit.framework.TestCase;
import junit.framework.TestSuite;

/**
 * @author undera
 */
public class InvalidPerfMonMetricTest extends TestCase {

    public InvalidPerfMonMetricTest() {
    }

    public static Test suite() {
        TestSuite suite = new TestSuite(InvalidPerfMonMetricTest.class);
        return suite;
    }

    /**
     * Test of getValue method, of class InvalidPerfMonMetric.
     */
    public void testGetValue() {
        System.out.println("getValue");
        StringBuffer res = new StringBuffer();
        InvalidPerfMonMetric instance = new InvalidPerfMonMetric();
        instance.getValue(res);
    }
}
