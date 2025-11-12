# Overview

<p align="center">
  <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Front.png" height="200" />
  <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Hardware/3DModel.gif" height="200" />
</p>

This device monitors the electrical fence of livestock enclosures. It’s a self-contained, connected, and easy-to-use system that alerts farmers when the fence voltage drops — a sign that the fence may be damaged or that animals might escape.
Communication is handled through the **SigFox** network.

# Background

An [electric fence](https://en.wikipedia.org/wiki/Electric_fence) is used in agriculture to keep animals inside a designated area.
It periodically generates a high-voltage pulse (around 10,000 V for 1 ms) between the ground and the fence wire. When an animal standing on the ground touches the wire, it receives a short, harmless shock that deters it from crossing.

<p align="center">
  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Electric_fence_01.jpg/220px-Electric_fence_01.jpg" />
</p>

A common issue is that fences lose efficiency over time: broken wires or vegetation touching the line can reduce voltage, making the shock too weak to be felt by animals. This often results in livestock escaping the enclosure.

# Concept

The idea was to build a simple, autonomous device connected to both the ground and the fence wire. It measures the fence voltage regularly (for example, every hour).
If the voltage drops below a set threshold (e.g. 2000 V), it automatically sends an alert to the farmer.

# State of the Art

In early 2015, connected devices were starting to emerge, but the agricultural sector had very few IoT solutions beyond weather stations for crop farmers.
Existing electric fence monitoring products mainly relied on GSM modules — power-hungry and expensive due to SIM card subscriptions.

That same year, the **SigFox** network appeared, offering long-range, ultra-low-power communication for IoT devices. Its limited data throughput (a few bytes per hour) was perfect for this kind of periodic measurement, with an annual subscription cost of under €10 per device.

<p align="center">
  <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/Couverture%20Sigfox.JPG" />
</p>

# Prototype

## Electronics

See: [Hardware Repository](https://github.com/Rufus31415/Connected-electric-fence-sensor/tree/master/Hardware)

SigFox recommends several compatible microcontrollers (see [list](https://partners.sigfox.com/products/soc)).
This prototype uses the **AX-SFEU** chip from **ON Semiconductor** ([datasheet](http://www.onsemi.com/PowerSolutions/product.do?id=AX-SFEU)).

<p align="center">
  <img src="https://github.com/Rufus31415/Connected-electric-fence-sensor/blob/master/Hardware/AX-SFEU_BlocDiagram.PNG" />
</p>

It integrates a 16-bit core, radio transceiver, GPIOs, ADCs, and timers.
Its ultra-low-power design allows deep sleep modes between transmissions.
Below is the schematic showing the radio circuitry and passive components:

<p align="center">
  <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Hardware/SchematicRadio.PNG" />
</p>

The measurement circuit uses two resistors as a 1:10,000 voltage divider to bring the fence voltage down to a safe level for the microcontroller. A Zener diode protects against high voltages or reverse currents.

<p align="center">
  <img src="https://github.com/Rufus31415/Connected-electric-fence-sensor/blob/master/Hardware/SchematicRadio.PNG" />
</p>

A ¼-wave 868 MHz whip antenna (SMA connector) is mounted through the enclosure.

Power comes from two **SAFT LS14250 3.6 V 1.2 Ah lithium batteries**, giving an autonomy of about **3 years** for 10 measurements and transmissions per day.

The PCB routing keeps high-voltage and RF sections physically separated.

<p align="center">
  <img src="https://github.com/Rufus31415/Connected-electric-fence-sensor/blob/master/Hardware/Routage.PNG" />
</p>

## Mechanical Design

<p align="center">
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Hardware/3DModel.gif" height="200" />
</p>

The enclosure is **IP64-rated** and weatherproof.
To measure voltage, it must be connected both to ground and to the fence wire.
Two metal clips on the back connect to a metal post or rod (serving as ground) and automatically power the device.
The side ring connects to the fence wire.

<p align="center">
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/Power.gif" height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Front.png" height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Side.png" height="200" />
</p>

## Embedded Software

The **AX-SFEU** is programmable using its official development kit.
It’s based on an **8052 core**, and the firmware was compiled using **IAR for 8052** within **AxCode::Blocks** ([site](http://www.codeblocks.org/)).

<p align="center">
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/CodeBlocks.PNG" height="200" />
</p>

Source code: [Embedded Software](https://github.com/Rufus31415/Connected-electric-fence-sensor/tree/master/EmbeddedSoftware)

Due to a signed NDA with ON Semiconductor, radio-related code cannot be published; it’s replaced by comments noting the confidentiality.

### Algorithm Summary

* Device wakes up every 2 hours (deep sleep mode in between)
* Samples the divided voltage at 100 kHz for 1 s
* Stores the peak voltage detected
* Sends the measurement through SigFox
* Returns to deep sleep for 2 hours

## Backend

A PHP-based **WordPress plugin** was developed:
[ConnectedFencePlugin](https://github.com/Rufus31415/Connected-electric-fence-sensor/tree/master/Web/wp-content/plugins/ConnectedFencePlugin)

WordPress was chosen for its user management, plugin ecosystem, and customizable themes.
The plugin allows users to register new sensors by entering their device ID and visualize collected data directly from the SigFox backend via CURL requests (no local storage).

## Testing

Users can visualize voltage trends over time, making it easy to detect drops:

<p align="center">
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/Samples.PNG" />
</p>

Ten units were built and field-tested successfully:

<p align="center">
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Soldering.png" height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Collection1.png"  height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Collection2.png"  height="200" />
  <br />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Test1.png"  height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Test2.png"  height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Test3.png"  height="200" />
 <img src="https://raw.githubusercontent.com/Rufus31415/Connected-electric-fence-sensor/master/Docs/V1Test4.png"  height="200" />
</p>
