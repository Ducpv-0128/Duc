# Giải thích ý nghĩa một số từ
-br-int (bridge-integration) là một bridge ảo được tạo ra trên compute node để kết nối các mạng ảo (virtual network) và các instance (máy ảo). Br-int là một phần của Open vSwitch (OVS), là một switch ảo được sử dụng để quản lý các kết nối mạng ảo trong hệ thống OpenStack.
- Br-int có các tác dụng quan trọng sau:
	- Quản lý các kết nối mạng ảo: Br-int được sử dụng để kết nối các mạng ảo được tạo ra 	trên compute node với nhau và với các thiết bị mạng khác như router, tường lửa, switch 	vật lý, để cung cấp kết nối mạng cho các instance (máy ảo) trong hệ thống OpenStack.
	- Kết nối với các physical interface: Br-int kết nối với các physical interface (giao 	diện vật lý) trên compute node để cho phép các gói tin mạng được chuyển đến và từ các 	instance và mạng ảo.
	- Xử lý traffic giữa các mạng ảo: Br-int cho phép xử lý traffic giữa các mạng ảo, cũng 	như các gói tin được gửi đến các instance thông qua các mạng ảo khác nhau. Nó cũng cung 	cấp tính năng filtering và switching để xử lý traffic giữa các mạng ảo và instance.
	- Giảm tải cho controller node: Br-int giúp giảm tải cho controller node bằng cách xử 	lý các gói tin mạng trực tiếp trên compute node thay vì phải gửi tất cả traffic tới 	controller node để xử lý


-  br-provider (bridge-provider) là một bridge ảo được tạo ra trên compute node để kết nối các mạng vật lý (physical network) với các mạng ảo (virtual network) được tạo ra trên hệ thống OpenStack.
- Br-provider có các tác dụng quan trọng sau:
	- Kết nối các mạng vật lý: Br-provider kết nối các mạng vật lý như switch vật lý, router, firewall với các mạng ảo được tạo ra trên hệ thống OpenStack. Điều này cho phép các instance (máy ảo) trong hệ thống OpenStack kết nối với các mạng vật lý bên ngoài.
	- Cấu hình các VLAN: Br-provider cho phép tạo và cấu hình các VLAN (Virtual LAN) trên các mạng vật lý để giúp chia sẻ băng thông mạng và cải thiện hiệu suất mạng.
	- Tách biệt traffic: Br-provider giúp tách biệt traffic giữa các mạng vật lý khác nhau, để giảm thiểu tác động của các sự cố mạng có thể xảy ra trên một mạng vật lý.
	- Giúp định tuyến traffic: Br-provider giúp định tuyến traffic giữa các mạng vật lý và các mạng ảo, để đảm bảo các gói tin được chuyển đến đúng đích.
-br-tun(bridge-tunnel) là một trong các bridge ảo được tạo ra trên compute node trong hệ thống OpenStack. Br-tun có các tác dụng quan trọng sau:
	- Tạo các overlay network: Br-tun được sử dụng để tạo các overlay network, cho phép các instance (máy ảo) trong hệ thống OpenStack kết nối với nhau qua mạng overlay mà không cần thông qua mạng vật lý. Overlay network này giúp tăng tính linh hoạt và khả năng mở rộng của hệ thống OpenStack.
	- Tạo các tunnel: Br-tun sử dụng các giao thức tunneling như VXLAN, GRE, hoặc GENEVE để tạo ra các tunnel giữa các compute node trong hệ thống OpenStack. Các tunnel này cho phép các gói tin được truyền qua lại giữa các compute node trên các mạng vật lý khác nhau mà không bị gián đoạn.
	- Đảm bảo an ninh mạng: Br-tun giúp đảm bảo an ninh mạng bằng cách cho phép các mạng ảo được cô lập hoàn toàn với các mạng vật lý khác.
	- Giảm thiểu tốn kém băng thông: Br-tun giúp giảm thiểu tốn kém băng thông trên các mạng vật lý bằng cách cho phép các gói tin truyền qua lại giữa các compute node thông qua các tunnel riêng.




# Network Traffic flow - North/South

Traffic flow North-South được sử dụng để mô tả luồng dữ liệu từ bên ngoài của hạ tầng đến các máy chủ và máy ảo trong cloud và ngược lại

Khi một máy chủ hoặc máy ảo muốn truy câp ra ngoài hạ tầng. các gói tin truy cập phải trải qua một số bước xử lý để đến đích. cụ thể, traffic flow North-South sẽ trải qua các bước sau:

- Các gói tin từ instance sẽ đi qua security group bridge trên compoute node để xử lý và forwarding gói tin. 
- gói tin sẽ được chuyển tới bridge ảo br-int ( được sử dụng để quản lý các kết nối mạng ảo trong hệ thống openstack)
- sau khi được br-int xử lý gói tin sẽ được chuyển tới br-provider, br-provider sẽ xử lý và chuyển gói tin ra hạ tầng mạng vật lý 
- gói tin đi qua switch rồi chuyển đến router or tường lửa rồi chuyển gói tin ra bên ngoài

với mạng self-service thì gói tin của instance trên compute node sẽ đi qua br-tun để đến Network node. Trên Network node cũng thực hiện tương tự các bước ở trên


# Network traffic flow East-west
- Trong mô hình mạng East-west scenario 1 của OpenStack, các gói tin di chuyển giữa các instance nằm trên các compute node khác nhau. Luồng đi của gói tin được giải thích như sau:
	- Gói tin xuất phát từ một instance trên một compute node và được gửi đến interface tap0 của instance đó.
	- Gói tin được đánh dấu với VLAN ID tương ứng với network của instance.
	- Gói tin được chuyển đến bridge trung gian trên compute node, ví dụ: br-int.
	- Tại br-int, Open vSwitch (OVS) sẽ kiểm tra bảng truyền thông để xác định địa chỉ MAC của instance đích. Nếu địa chỉ này đã có trong bảng, OVS sẽ chuyển gói tin trực tiếp đến đích. 	Nếu không, OVS sẽ gửi yêu cầu ARP broadcast để tìm kiếm địa chỉ MAC đích.
	- Khi địa chỉ MAC đích được xác định, gói tin sẽ được chuyển đến bridge trung gian trên compute node của instance đích, ví dụ: br-int.
	- Tại br-int của instance đích, gói tin sẽ được xác định xem có đến đúng địa chỉ của instance đó không. Nếu đúng, gói tin sẽ được gửi đến interface tap0 của instance đó.
	- Gói tin được giải mã VLAN và gửi đến instance đích.


- Trong mô hình mạng East-West scenario 2  của OpenStack, khi các instance trên cùng một Compute Node nhưng thuộc các mạng khác nhau muốn giao tiếp với nhau, luồng đi của gói tin sẽ theo các bước sau:
	- Gói tin được gửi từ VM1 thuộc mạng A đi đến bridge br-int.
	- br-int kiểm tra bảng MAC để xác định MAC address của VM2 thuộc mạng B có đang được lưu trữ trên Compute Node không. Nếu không có trong bảng MAC, br-int sẽ broadcast gói tin đến tất cả các VM thuộc cùng mạng và lưu lại thông tin MAC address của VM2.
	- Gói tin được chuyển tiếp từ br-int đến br-tun, trong đó nó sẽ được đóng gói vào một gói tin GRE (Generic Routing Encapsulation) hoặc VXLAN (Virtual Extensible LAN) và đi qua mạng truyền thông dữ liệu (underlay network).
	- Gói tin được nhận bởi bridge br-tun trên Compute Node đích, nó sẽ được giải mã và giải nén để lấy lại gói tin ban đầu.
	- br-tun kiểm tra bảng MAC để xác định địa chỉ MAC của VM2 thuộc mạng B có đang được lưu trữ trên Compute Node không. Nếu không có trong bảng MAC, br-tun sẽ broadcast gói tin đến tất cả các VM thuộc cùng mạng và lưu lại thông tin MAC address của VM2.
	- Gói tin được chuyển tiếp từ br-tun đến br-int trên Compute Node đích.
	- Gói tin được gửi đến VM2 thuộc mạng B.
	
